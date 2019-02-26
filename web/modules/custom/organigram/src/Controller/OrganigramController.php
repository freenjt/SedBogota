<?php
/**
 * Created by PhpStorm.
 * User: macbookpro
 * Date: 2019-02-15
 * Time: 16:24
 */

namespace Drupal\organigram\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Drupal\taxonomy\Entity\Term;

class OrganigramController extends ControllerBase
{
    public $vid = 'organigram';
    public $terms = [];
    public $elements_before = [];


    public function __construct()
    {
        $terms = Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($this->vid);
    }

    function content($term = NULL)
    {

        $term_element = [];
        $term_before = NULL;
        foreach ($this->terms as $value) {
            $term = Term::load($value->tid);
            $term_element = $this->getTerms($term);

          /*  $link = NULL;
            if (count($term->get('field_link')->getValue())) {
                $link = $term->get('field_link')->getValue()[0]['uri'];
            }
            if ($value->parents[0]) {
                $term[$value->tid];
            }
            $term[$value->tid] = [
                'name' => $value->name,
                'link' => $link,
            ];*/
        }
        $build = [
            '#theme' => 'organigram',
            '#term_element' => '',
            '#attached' => [
                'library' => [
                    'organigram/organigram-library',
                ],
            ],
        ];
        return $build;
    }

    function getTerms(&$term = NULL,  $flag = TRUE, $term_elements = [])
    {
        //$term = Term::load($term_root->tid);

        $link = NULL;
        if (count($term->get('field_link')->getValue())) {
            $link = $term->get('field_link')->getValue()[0]['uri'];
        }
        if ($term->parents[0] && $flag) {
            $term_elements[$term->parents[0]]['children'][$term->tid] = $this->getTerms($term, FALSE, $this->elements_before);
            $this->elements_before = $term_elements[$term->parents[0]]['children'];
        } else {
            $term_elements[$term->tid] = [
                'name' => $term->name,
                'link' => $link,
                'children' => [],
            ];
        }

        return $term_elements;
    }
}