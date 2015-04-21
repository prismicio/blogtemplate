<?php

function theme_dir($app)
{
    return __DIR__ . '/../themes/' . $app->config('theme');
}

function render_include($app, $page)
{
    require theme_dir($app) . '/' . $page . '.php';
}

function render($app, $page, $data = array())
{
    global $wp_query, $loop, $WPGLOBAL;
    $loop = new Loop();

    foreach($data as $key => $value)
    {
      if($key == 'posts'){
        $loop->setResponse($value);
      }
      if($key == 'single_post'){
        $loop->setPosts(array($value));
      }
      if($key != 'posts'){
        $WPGLOBAL[$key] = $value;
      }

    }

    $wp_query = new WP_Query();
    // Optional helpers that theme developers can provide
    try {
        include_once(theme_dir($app) . '/functions.php');
    } catch(Exception $ex) {}
    $file_path = theme_dir($app) . '/' . $page . '.php';
    if (file_exists($file_path)) {
        require($file_path);
    } else {
        render($app, '404');
    }
}

function current_page($app)
{
    $pageQuery = $app->request()->params('page');
    return $pageQuery == null ? '1' : $pageQuery;
}

function not_found($app)
{
    $app->response->setStatus(404);
    render($app, '404');
}

function check_page_path1($path, $prismic)
{
    $uid = end($path);
    $correctAddress = $prismic->page_path($uid);
    if($path == $correctAddress) {
      return $uid;
    }
}

function redirect_path($path, $prismic)
{
    $npath = $prismic->refresh_path($path);
    if ($npath == null) return null;

    $npath_uid = end($npath);
    $newCorrectAddress = $prismic->page_path($npath_uid);
    return '/'.implode('/',$newCorrectAddress);
}

function check_page_path($path, $prismic, $app)
{
    $page_uid = check_page_path1($path, $prismic);

    if($page_uid == null)
    {
        $redirect_url = redirect_path($path, $prismic);
        if($redirect_url != null)
        {
            $app->response->redirect($redirect_url);
        }
        if($redirect_url == null)
        {
            not_found($app);
        }
    }

    return $page_uid;

}
