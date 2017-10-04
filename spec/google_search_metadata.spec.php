<?php

 describe(\NHSEngland\GoogleSearchMetadata::class, function () {
     beforeEach(function () {
         \WP_Mock::setUp();
         $this->googleSearchMetadata = new \NHSEngland\GoogleSearchMetadata();
         $this->post = new stdClass();
         $this->post->ID = 123;
         $this->post->post_type = 'blog';
         global $post;
         $post = $this->post;
         $this->category1 = new stdClass();
         $this->category1->slug = "category-1";
         $this->category2 = new stdClass();
         $this->category2->slug = "category-2";
     });

     afterEach(function () {
         \WP_Mock::tearDown();
     });

     it('is registerable', function () {
         expect($this->googleSearchMetadata)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
     });

     describe('->register()', function () {
         it('adds an action', function () {
             WP_Mock::expectActionAdded('wp_head', [$this->googleSearchMetadata, 'addGoogleSearchMetadata']);
             $this->googleSearchMetadata->register();
         });
     });

     describe('->addGoogleSearchMetadata', function () {
         context('is not a singular post', function () {
             it('does nothing', function () {
                 WP_Mock::wpFunction('is_singular', [
                    'times' => 1,
                    'return' => false,
                ]);
                 ob_start();
                 $this->googleSearchMetadata->addGoogleSearchMetadata();
                 $result = ob_get_clean();
                 expect($result)->to->equal("");
             });
         }) ;
         context('is singular', function () {
             it('echoes meta tags for the date in YYYYMMDD format, the categories, and the post type', function () {
                 WP_Mock::wpFunction('is_singular', [
                    'times' => 1,
                    'return' => true,
                ]);
                 WP_Mock::wpFunction('get_the_date', [
                    'times' => 1,
                    'args' => 'Ymd',
                    'return' => '20170214'
                ]);
                 WP_Mock::wpFunction('get_the_category', [
                    'times' => 1,
                    'args' => 123,
                    'return' => [
                        $this->category1,
                        $this->category2
                    ]
                ]);
                 ob_start();
                 $this->googleSearchMetadata->addGoogleSearchMetadata();
                 $result = ob_get_clean();
                 expect($result)->to->equal("<meta name='category' content='category-1'>\r\n<meta name='category' content='category-2'>\r\n<meta name='post-type' content='blog'>\r\n<meta name='pubdate' content='20170214'>\r\n");
             });
         });
     });
 });
