<?php

namespace Statbus\Controllers;

use Psr\Container\ContainerInterface;
use Statbus\Controllers\Controller as Controller;
use Statbus\Models\Library as Library;
use Statbus\Controllers\UserController as User;

class LibraryController Extends Controller {
  
  public function __construct(ContainerInterface $container) {
    parent::__construct($container);
    $this->pages = ceil($this->DB->cell("SELECT count(tbl_library.id) FROM tbl_library WHERE tbl_library.content != ''
      AND (tbl_library.deleted IS NULL OR tbl_library.deleted = 0)") / $this->per_page);

    $this->libraryModel = new Library();

    $this->breadcrumbs['Library'] = $this->router->pathFor('library.index');
  }

  public function index($request, $response, $args) {
    if(isset($args['page'])) {
      $this->page = filter_var($args['page'], FILTER_VALIDATE_INT);
    }
    $books = $this->DB->run("SELECT 
      tbl_library.id,
      tbl_library.author,
      tbl_library.title,
      tbl_library.category,
      IF('Adult' = tbl_library.category, 1, 0) AS nsfw
      FROM tbl_library
      WHERE tbl_library.content != ''
      AND (tbl_library.deleted IS NULL OR tbl_library.deleted = 0)
      ORDER BY tbl_library.datetime DESC
      LIMIT ?,?", ($this->page * $this->per_page) - $this->per_page, $this->per_page);
    foreach ($books as &$book) {
      $book = $this->libraryModel->parseBook($book);
    }
    return $this->view->render($response, 'library/listing.tpl',[
      'books'      => $books,
      'library'       => $this,
      'breadcrumbs' => $this->breadcrumbs
    ]);
  }

  public function single($request, $response, $args) {
    $book = $this->getBook($args['id']);
    return $this->view->render($response, 'library/single.tpl',[
      'book'       => $book,
      'breadcrumbs' => $this->breadcrumbs,
      'ogdata'      => $this->ogdata
    ]);
  }

  public function getBook(int $id){
    $id = filter_var($id, FILTER_VALIDATE_INT);
    $book = $this->DB->row("SELECT
      L.id,
      L.author,
      L.title,
      L.content,
      L.category,
      L.ckey,
      L.datetime,
      L.deleted,
      L.round_id_created,
      IF('Adult' = L.category, 1, 0) AS nsfw
      FROM tbl_library AS L
      WHERE L.id = ?", $id);
    $book = $this->libraryModel->parseBook($book);
    $url = parent::getFullURL($this->router->pathFor('library.single',['id'=>$book->id]));
    if(!$book->deleted) {
      $this->breadcrumbs[$book->title] = $url;
    } else {
      $this->breadcrumbs['[Book Deleted]'] = $url;
    }
    return $book;
  }

  public function deleteBook($request, $response, $args){
    $id = filter_var($args['id'], FILTER_VALIDATE_INT);
    $book = $this->getBook($id);
    $url = parent::getFullURL($this->router->pathFor('library.single',['id'=>$book->id]));
    if(FALSE === $request->getAttribute('csrf_status')){
      return $this->view->render($response, 'base/error.tpl',[
        'message'  => "CSRF failure. This action is denied.",
        'code'     => 403,
        'link'     => $url,
        'linkText' => 'Back'
      ]);
    }
    $user = (new User($this->container))->fetchUser();
    if (!$user->canAccessTGDB) {
      return $this->view->render($response, 'base/error.tpl',[
        'message' => "You do not have permission to access this page.",
        'code'    => 403
      ]);
    }
    $delete = TRUE;
    if($book->deleted){
      $delete = FALSE;
    }
    $this->DB->update('tbl_library',[
      'deleted' => $delete
    ],[
      'id' => $id
    ]);
    $book = $this->getBook($id);
    return $this->view->render($response, 'library/single.tpl',[
      'book'        => $book,
      'breadcrumbs' => $this->breadcrumbs,
      'ogdata'      => $this->ogdata
    ]);
  }
}