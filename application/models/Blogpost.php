<?php
use Illuminate\Database\Eloquent\Model as Eloquent;
class Blogpost extends Eloquent {
    protected $fillable = ['title', 'url', 'image', 'date'];
}
?>
