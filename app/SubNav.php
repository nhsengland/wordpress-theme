<?php

namespace NHSEngland;

use Dxw\Iguana\Registerable;

class SubNav implements Registerable
{
    protected $currentPageId;
    protected $classes = '';
    public $output = '';
    protected $levels = [];

    public function __construct()
    {
        $this->siteId = get_current_blog_id();
    }

    public function register()
    {
    }

    public function subNav($currentPageId, $classes = '', $levels = [])
    {
        $this->currentPageId = $currentPageId;
        $this->classes = $classes;
        return $this->output($levels);
    }

    public static function generate($currentPageId, $classes, $levels = [])
    {
        $class = new self;
        return $class->subNav($currentPageId, $classes, $levels);
    }

    public static function create($levels, $classes)
    {
        $class = new self;
        $class->setLevels($levels);
        $class->addClass($classes);
        $class->buildHtml();
        return $class->output;
    }

    public function addTopLevel()
    {
        if (is_multisite()) {
            $this->addLevel('top', get_blog_details()->blogname, site_url());
        }

        if ($this->isMainSite()) {
            $top = $this->getTopLevelAncestor();
            $this->addLevel('top', get_the_title($top), get_the_permalink($top), $top);
        }
    }

    public function addParentLevel()
    {
        $parent = $this->getTopLevelAncestor();
        $this->addLevel('parent', get_the_title($parent), get_the_permalink($parent), $parent);

        if ($this->isMainSite()) {
            $this->removeLevel('parent');

            if ($this->hasAncestors(1)) {
                $this->addLevel('parent', get_the_title($this->currentPageId), get_the_permalink($this->currentPageId), $this->currentPageId);
            }

            if ($this->hasAncestors(2)) {
                $parent = $this->getSecondLevelAncestor();
                $this->addLevel('parent', get_the_title($parent), get_the_permalink($parent), $parent);
            }
        }
    }

    public function addChildren()
    {
        $children = '';
        $pageId = wp_get_post_parent_id($this->currentPageId);


        if (isset($this->levels['parent'])) {
            $pageId  = $this->levels['parent']['id'];
        }

        if ($pageId === 0 || is_null($pageId)) {
            $children = $this->getChildrenOf($this->currentPageId, true, true);
        }
        if ($pageId == 0 && empty($children) && !$this->isMainSite()) {
            $this->levels['siblings'] = $this->getTopLevelPages(['exclude' => $this->getFrontPageId()]);
        }

        if ($pageId > 0) {
            $children = $this->getChildrenOf($pageId, true, false);
        }

        if (!empty($children) && !$this->isMainSite()) {
            $this->levels['siblings'] = $this->getTopLevelPages(['exclude' => $this->getFrontPageId().','.$pageId]);
        }

        $this->levels['children'] = $children;
    }

    protected function getChildrenOf($pageId, $excludePage = false, $onlyDirect = false)
    {
        $query = 'title_li=&sort_column=menu_order&echo=0&';
        if ($excludePage) {
            $query .= '&child_of='.$pageId.'&exclude='.$pageId;
        }
        if ($onlyDirect) {
            $query .= '&child_of='.$this->currentPageId.'&depth=1';
        }
        return wp_list_pages($query);
    }

    protected function getTopLevelPages($args = null)
    {
        $exclude = [$this->currentPageId];

        if (!is_null($args) && isset($args['exclude'])) {
            $exclude[] = $args['exclude'];
        }
        return wp_list_pages('title_li=&sort_column=menu_order&echo=0&depth=1&exclude='.implode(',', $exclude));
    }

    public function removeLevel($level)
    {
        unset($this->levels[$level]);
    }

    public function addLevel($level, $label, $link, $id = 0)
    {
        $this->levels[$level] = [
            'id' => $id,
            'label' => $label,
            'link'  => $link
        ];
    }

    public function setLevels($levels = [])
    {
        $this->levels = array_merge($this->levels, $levels);
    }

    protected function hasAncestors($num)
    {
        $this->getAncestors();
        return isset($this->ancestors[count($this->ancestors)-$num]);
    }

    protected function getTopLevelAncestor()
    {
        $this->getAncestors();
        return isset($this->ancestors[count($this->ancestors)-1]) ? $this->ancestors[count($this->ancestors)-1] : null;
    }

    protected function getSecondLevelAncestor()
    {
        $this->getAncestors();
        return isset($this->ancestors[count($this->ancestors)-2]) ? $this->ancestors[count($this->ancestors)-2] : null;
    }

    protected function getAncestors()
    {
        return $this->ancestors = get_post_ancestors($this->currentPageId);
    }

    protected function isMainSite()
    {
        return $this->siteId === 1;
    }

    public function addClass($class)
    {
        $this->classes .= $class;
    }

    public function getDepth($pageId)
    {
        $parent  = $pageId;
        $depth = 0;
        while ($parent > 0) {
            $page = get_page($parent);
            $parent = $page->post_parent;
            $depth++;
        }
        return $depth;
    }

    public function buildHtml()
    {
        $minimalLevel = ($this->isMainSite()) ? 4 : 3;
        $depthClass = ($this->getDepth($this->currentPageId) >= $minimalLevel) ? 'minimal_nav' : 'normal_nav';

        $this->output .= '<aside class="subnav group '.$depthClass.' '.$this->classes.'" role="complementary">';
        $this->output .= '<div class="sub-nav">';
        $this->output .= '<nav class="nav-collapse"><ul>' . "\n";

        if (isset($this->levels['top'])) {
            $this->output .= '<li class="top">';
            $this->output .= '<a href="' . $this->levels['top']['link'] . '">' . $this->levels['top']['label'] . '</a>';
            $this->output .= '</li>';
        }
        if (isset($this->levels['parent'])) {
            $this->output .= '<li class="parent">';
            $this->output .= '<a href="' . $this->levels['parent']['link'] . '">' . $this->levels['parent']['label'] . '</a>';
            $this->output .= '</li>';
        }
        if (isset($this->levels['children']) && !empty($this->levels['children'])) {
            $this->output .= '<li class="children-nav"><ul>';
            $this->output .= $this->levels['children'];
            $this->output .= '</ul></li>';
        }
        if (isset($this->levels['siblings']) && $this->getDepth($this->currentPageId) < $minimalLevel) {
            $this->output .= $this->levels['siblings'];
        }
        $this->output .= '</ul></nav>' . "\n";
        $this->output .= '</div>';
        $this->output .= '</aside>';
    }

    public function output($levels = [])
    {
        $this->addTopLevel();
        $this->addParentLevel();
        $this->addChildren();

        $this->setLevels($levels);
        //build html
        $this->buildHtml();

        return $this->output;
    }

    private function getFrontPageId()
    {
        return get_option('page_on_front');
    }
}
