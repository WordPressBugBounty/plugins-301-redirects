<?php
class Redirects
{
  function delete()
  {
    global $wpdb;

    $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}ts_redirects");
  } // delete


  function edit($title, $section, $new_link, $old_link)
  {
    global $wpdb;
    //phpcs:ignore because we are using a custom table for redirect rules
    $wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}ts_redirects (title, section, new_link, old_link) VALUES (%s, %s, %s, %s)", array($title, $section, $new_link, $old_link))); //phpcs:ignore
  } // edit


  function getFields($id)
  {
    global $wpdb;

    $result = $wpdb->query($wpdb->prepare("SELECT * FROM {$wpdb->prefix}ts_redirects WHERE id = %d", array($id))); //phpcs:ignore
    if ($result !== 0) {
      $fields = array();
      foreach ($wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}ts_redirects WHERE id = %d", array($id))) as $row) { //phpcs:ignore
        $fields['title'] = $row->title;
        $fields['section'] = $row->section;
        $fields['new_link'] = $row->new_link;
        $fields['old_link'] = $row->old_link;
      }

      return $fields;
    } else {
      return false;
    }
  } // getFields


  function createRedirectsTable()
  {
    global $wpdb;

    $wpdb->query("CREATE TABLE {$wpdb->prefix}ts_redirects (id BIGINT(20) PRIMARY KEY AUTO_INCREMENT,title TEXT,section TEXT, new_link TEXT, old_link TEXT)"); //phpcs:ignore
  } // createRedirectsTable


  function checkForRedirectsTable()
  {
    global $wpdb;

    $result = $wpdb->get_results("SHOW TABLES LIKE 'ts_redirects'"); //phpcs:ignore
    if (sizeof($result) == 1) {
      $wpdb->query("RENAME TABLE ts_redirects TO {$wpdb->prefix}ts_redirects"); //phpcs:ignore
    }

    $result = $wpdb->get_results("SHOW TABLES LIKE '{$wpdb->prefix}ts_redirects'"); //phpcs:ignore
    if (sizeof($result) != 1) {
      $this->createRedirectsTable();
    }
  } // checkForRedirectsTable


  function getAll()
  {
    global $wpdb;

    $this->checkForRedirectsTable();

    $result = $wpdb->query("SELECT * FROM {$wpdb->prefix}ts_redirects ORDER by id ASC"); //phpcs:ignore
    if ($result !== 0) {

      $id_arr = array();
      foreach ($wpdb->get_results("SELECT * FROM {$wpdb->prefix}ts_redirects ORDER by id ASC") as $row) { //phpcs:ignore
        $id_arr[] = $row->id;
      }

      return $id_arr;
    } else {
      return false;
    }
  } // getAll


  function remove($custom_id)
  {
    global $wpdb;

    $wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}ts_redirects WHERE id = %d", array($custom_id))); //phpcs:ignore
  }
} // remove

$redirectsplugin = new Redirects();
$GLOBALS['redirectsplugins'] = $redirectsplugin;
