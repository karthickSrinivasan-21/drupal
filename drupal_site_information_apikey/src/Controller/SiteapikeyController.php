<?php

namespace Drupal\drupal_site_information_apikey\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\node\NodeInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Controller routines for SiteapikeyController routes.
 */
class SiteapikeyController extends ControllerBase {
  
  /**
   * {@inheritdoc}
   */
  public function siteapikeyvalidation(NodeInterface $node, $siteapikey) {
    $current_path = \Drupal::request()->getRequestUri();
    $current_parameter = explode('/', $current_path);
    $system_site_informatin_cofig_values = \Drupal::service('config.factory')->getEditable('system.site');
    $site_api_key = $system_site_informatin_cofig_values->get('siteapikey');
    $base_url = \Drupal::request()->getSchemeAndHttpHost();
    $get_all_nids = \Drupal::database()->select('node', 'n')
      ->fields('n', ['nid'])
      ->condition('n.nid', $current_parameter[3]);
    $results = $get_all_nids->execute();
    $nid = [];
    while ($content = $results->fetchAssoc()) {
      $nid = $content;
    }
    if ($current_parameter[4] == $site_api_key && $nid['nid'] == $current_parameter[3]) {
      return [
        '#markup' => '<p>Access this page. <a href=' . $base_url . '/node/' . $current_parameter[3] . '>Please clik the url</a></p>',
      ];
    }
    else {
      throw new AccessDeniedHttpException();
    }
    return new JsonResponse();
  }

}
