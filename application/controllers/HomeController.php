<?php
require_once(realpath(dirname(__FILE__) . "/../../resources/config.php"));
require_once(APP_PATH . '/core/Controller.php');

class HomeController extends Controller {

    public function indexAction() {
        $this->view('home/index');
    }

    public function getBlogPostsAction() {
        $url = 'http://devcouch.net/wp-json/wp/v2/posts';
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($curl);
        $posts = json_decode($result);
        $blogPosts = [];
        $count = 5;
        foreach ($posts as $post) {
            $blogPost = [
                'title' => $post->title->rendered,
                'link' => $post->link,
                'image' => $this->getBlogPostImage($post->featured_image)
            ];
            $blogPosts[] = $blogPost;
            if (count($blogPosts) >= $count) {
                break;
            }
        }
        $json = json_encode($blogPosts);
        echo $json;

    }

    public function getBlogPostImage($id) {
        $mediaUrl = 'http://devcouch.net/wp-json/wp/v2/media/' . $id;
        $curl = curl_init($mediaUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $media = json_decode(curl_exec($curl));
        return $media->media_details->sizes->medium->source_url;
    }
}

?>
