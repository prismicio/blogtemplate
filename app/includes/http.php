<?php

function current_page($app)
{
    $pageQuery = $app->request()->params('page');
    return $pageQuery == null ? '1' : $pageQuery;
}

function not_found($app, $theme)
{
  $app->response->setStatus(404);
  $theme->render('404');
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
  if($npath != null)
  {
    $npath_uid = end($npath);
    $newCorrectAddress = $prismic->page_path($npath_uid);
    if($npath == $newCorrectAddress)
    {
      return '/'.implode('/',$newCorrectAddress);

    }
    if($npath != $newCorrectAddress)
    {
      //404
      return null;
    }
  }

  return null;
}

function check_page_path($path, $prismic, $app)
{
  $theme = new Theme($app, $prismic);
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
      not_found($app, $theme);
    }
  }

  return $page_uid;

}
