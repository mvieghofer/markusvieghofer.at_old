<?php
require_once(realpath(dirname(__FILE__) . "/../../resources/config.php"));
require_once(APP_PATH . '/core/Controller.php');
require_once(APP_PATH . '/core/db.php');

class HomeController extends Controller {

    public function indexAction() {
        $blogPosts = Blogpost::orderBy('date', 'DESC')->limit(5)->get();
        $blogPostList = [];
        foreach ($blogPosts as $blogPost) {
            $blogPostObj = [
                'title' => $blogPost->title,
                'url' => $blogPost->url,
                'image' => $blogPost->image,
                'id' => $blogPost->id
            ];
            $blogPostList[] = $blogPostObj;
        }
        $this->view('home/index', $blogPostList);
    }

    public function getBlogPostsAction() {
        $url = 'http://devcouch.net/wp-json/wp/v2/posts';
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($curl);
        $posts = json_decode($result);
        $blogPosts = [];
        $count = 5;
        $insertValues = [];
        foreach ($posts as $post) {
            $blogPost = Blogpost::where('url', '=', $post->link)->first();
            if ($blogPost === null) {
                $blogPost = new Blogpost();
                $blogPost->title = $post->title->rendered;
                $blogPost->url = $post->link;
                $blogPost->image = $this->getBlogPostImage($post->featured_image);
                $blogPost->date = strtotime($post->date);
                $blogPost->save();
            } else {
                $uiBlogPost = new stdClass;
                $uiBlogPost->title = $blogPost->title;
                $uiBlogPost->url = $blogPost->url;
                $uiBlogPost->image = $blogPost->image;
                $uiBlogPost->id = $blogPost->id;
                $blogPost = $uiBlogPost;
            }
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
