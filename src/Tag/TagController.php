<?php

namespace Jen\Tag;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Jen\Tag\HTMLForm\SearchForm;
use Jen\Question\Question;
use Jen\Answer\Answer;

/**
 * Tag controller
 */
class TagController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * Redirect to search page.
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {
        return $this->di->response->redirect("tag/search");
    }

     /**
     * Search for tags.
     *
     * @return object as a response object
     */
    public function searchAction() : object
    {
        $page = $this->di->get("page");

        $tag = new Tag();
        $tag->setDb($this->di->get("dbqb"));
        $tags = $tag->getPopular(1000);

        $form = new SearchForm($this->di);
        $form->check();

        $page->add("tag/view-top-tags", [
            "tags" => $tags
        ]);

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ], "sidebar-right");

        return $page->render([
            "title" => "Tags",
        ]);
    }

    /**
     * Show search result of users.
     *
     * @return object as a response object
     */
    public function resultAction($searchQuery) : object
    {
        $page = $this->di->get("page");
     
        $tag = new Tag();
        $tag->setDb($this->di->get("dbqb"));
        $tagResults = $tag->findAllWhere("tag LIKE ?", "%$searchQuery%");
        $relevant = [];
        $selectedQuestionsID = [];

        foreach ($tagResults as $result) {
            $question = new Question();
            $question->setDb($this->di->get("dbqb"));
            $question = $question->findWhere("id = ?", $result->questionId);
    
            if (!in_array($question->id, $selectedQuestionsID)) {
                $result->question = $question;
                $answer = new Answer();
                $answer->setDb($this->di->get("dbqb"));
                $question->answersAmount = $answer->answersAmount($question->id);
                array_push($selectedQuestionsID, $question->id);
                array_push($relevant, $result);
            }
        }

        $form = new SearchForm($this->di);
        $form->check();

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ], "sidebar-right");

        
        $page->add("tag/view-all", [
            "tagResults" => $relevant,
            "searchQuery" => $searchQuery
        ]);

        return $page->render([
            "title" => "Tags",
        ]);
    }
}
