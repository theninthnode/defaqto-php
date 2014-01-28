DefaqtoIO PHP Client
####################

DefaqtoIO PHP is a PHP package for using the [DefaqtoIO](defaqto.io) CMS service.

Installation
------------

## Composer

Add the library to your composer.json file

    "theninthnode/defaqto-php": "1.0.*"

Run ```composer install``` to get the latest version of the package.

## Manually

It's recommended that you use Composer, however you can download and install from this repository.

Please note. This client requires the Guzzle REST PHP package.

## Laravel 4

This package comes with a Service Provider and Facade for easy integration with Laravel4.

1. Add the following entry to the ```providers``` array in config/app.php

    'TheNinthNode\Defaqto\DefaqtoServiceProvider'

2. Add the following entry to the ```aliases``` array in config/app.php

    'Defaqto' => 'TheNinthNode\Defaqto\Facades\Defaqto',

Usage
-----

1. Register your account at [defaqto.io](defaqto.io). 
2. Log in and create your first app by clicking the "+ Create New App" button.
3. In your new app click "Settings" from the left menu.
4. Take note of the the App ID and Access Token.
5. Create a test page.

After installing the package you can use it like this:

    $pages = Defaqto::setup($app_id, $access_token)->get('pages');
    var_dump($pages);

NOTE. If you get a class not found error load the class in with:

    use TheNinthNode\Defaqto\Defaqto;

OR

    $pages = TheNinthNode\Defaqto\Defaqto::setup($app_id, $access_token)->get('pages');


You can use the class with the following syntax:

    $html = Defaqto::setup($app_id, $access_token)->get('entity', array('key'=>'value'));

Where ```entity``` is one of ```pages```, ```blocks```, ```variables```, ```blog/posts```, ```blog/categories```, ```blog/tags```, ```blog/authors```.

The second (optional) param is an array of key=>value pairs known as attributes. ie. array('page_slug'=>'about-us')

Each entity has the following optional params:

* order - field to use for sorting
* dir - direction to sort ASC or DESC
* limit - limit the amount of results
* offset - number of results to skip

There is a third optional param for use with the blog/posts entity. This is an array of entities to exclude (tags, categories, authors)

## Examples

### Pages

Entity:

```pages```

Params:

* page_id
* page_slug

To get all pages:

    $pages = Defaqto::setup($app_id, $access_token)->get('pages');
    var_dump($pages);

To get a page by ```page_slug```

    $page = Defaqto::setup($app_id, $access_token)->get('pages', array('page_slug'=>'about-us'));
    echo $page['title'];

### HTML Blocks

Entity:

```pages```

Params:

* block_id

To get all blocks:

    $blocks = Defaqto::setup($app_id, $access_token)->get('blocks');
    var_dump($blocks);

To get a block by ```block_id```

    $block = Defaqto::setup($app_id, $access_token)->get('blocks', array('block_id'=>1234));
    echo $block['content'];

### Variables

Entity:

```variables```

Params:

* key

To get all variables:

    $variables = Defaqto::setup($app_id, $access_token)->get('variables');
    var_dump($variables);

To get a variable by it's ```key```

    $variable = Defaqto::setup($app_id, $access_token)->get('variables', array('key'=>'pricing'));
    echo $variable['value'];


### Blog posts

Entity:

```blog/posts```

Params:

* post_id
* post_slug
* category_slug
* tag_slug
* status - draft|published (defaults to published)

To get all (published) posts:

    $posts = Defaqto::setup($app_id, $access_token)->get('blog/posts');
    var_dump($posts);

To get a post by it's ```post_slug```

    $post = Defaqto::setup($app_id, $access_token)->get('blog/posts', array('post_slug'=>'hello-world'));
    echo $post['title'];

To get all posts by tag (```tag_slug```)

    $posts = Defaqto::setup($app_id, $access_token)->get('blog/posts', array('tag_slug'=>'javascript'));
    var_dump($posts);

To get all posts without their authors, tags, or categories

    $posts = Defaqto::setup($app_id, $access_token)->get('blog/posts', array(), array('authors', 'tags', 'categories'));

### Blog categories

Entity:

```blog/categories```

Params:

* category_id
* category_slug

To get all categories:

    $cats = Defaqto::setup($app_id, $access_token)->get('blog/categories');
    var_dump($cats);

To get a category by it's slug

    $category = Defaqto::setup($app_id, $access_token)->get('blog/categories', array('category_slug'=>'announcements'));
    echo $category['name'];

### Blog tags

Entity:

```blog/tags```

Params:

* tag_id
* tag_slug

To get all tags:

    $tags = Defaqto::setup($app_id, $access_token)->get('blog/tags');
    var_dump($tags);

To get a tag by it's slug

    $tag = Defaqto::setup($app_id, $access_token)->get('blog/tags', array('tag_slug'=>'javascript'));
    echo $tag['name'];

### Blog authors

Entity:

```blog/authors```

Params:

* author_id
* username

To get all authors:

    $authors = Defaqto::setup($app_id, $access_token)->get('blog/authors');
    var_dump($authors);

To get an author by their username

    $author = Defaqto::setup($app_id, $access_token)->get('blog/authors', array('username'=>'jonesy'));
    echo $author['bio'];
