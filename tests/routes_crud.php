<?php
/**
 * Test $_SERVER['REQUEST_METHOD']
 */

class blog_kuri
{
    function get()
    {
        return  'get';
    }

    function post(array $posts)
    {
        $result = '';

        if (count($posts > 0)) {
            foreach ($posts as $post) {
                $result .= $post.', ';
            }
        }

        return $result;
    }

    function put()
    {
        echo 'put';
    }
}
