<?php

use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\layout_builder\Section;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Implement Styles into Premium
 */
function premium_profile_deploy_implement_styles() {
  $pids = \Drupal::entityQuery('paragraph')
    ->condition('type', 'basic_hero')
    ->execute();

  $inner_pids = \Drupal::entityQuery('paragraph')
    ->condition('type', 'inline_hero')
    ->execute();

  $paragraphs = Paragraph::loadMultiple($pids);
  $themes = [];
  $positions = [];

  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  foreach ($paragraphs as $paragraph) {
    $field = $paragraph->get('field_color_theme');
    if (!$field->isEmpty()) {
      $themes[$paragraph->id()] = $field->getValue();
    }
    $field = $paragraph->get('field_text_position');
    if (!$field->isEmpty()) {
      $positions[$paragraph->id()] = $field->getValue();
    }
  }

  $paragraphs = Paragraph::loadMultiple($inner_pids);
  $inner_positions = [];

  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  foreach ($paragraphs as $paragraph) {
    $field = $paragraph->get('field_text_position');
    if (!$field->isEmpty()) {
      $inner_positions[$paragraph->id()] = $field->getValue();
    }
  }

  $entityTypemanager = \Drupal::entityTypeManager();

  $field = FieldConfig::loadByName('paragraph', 'basic_hero', 'field_text_position');
  if (!empty($field)) {
    $field->delete();
  }

  $field = FieldConfig::loadByName('paragraph', 'inline_hero', 'field_text_position');
  if (!empty($field)) {
    $field->delete();
  }

  $field_storage = FieldStorageConfig::loadByName('paragraph', 'field_text_position');
  if (!empty($field_storage)) {
    $field_storage->delete();
  }
  $field_storage = FieldStorageConfig::create([
    'field_name' => 'field_text_position',
    'langcode' => \Drupal::languageManager()->getDefaultLanguage()->getId(),
    'entity_type' => 'paragraph',
    'type' => 'styles',
    'settings' => ['collection' => 'hero_text_align'],
    'module' => 'styles',
    'locked' => FALSE,
    'cardinality' => 1,
    'translatable' => TRUE
  ]);
  $field_storage->save();

  $field = FieldConfig::loadByName('paragraph', 'basic_hero', 'field_text_position');
  if (empty($field)) {
    $field = FieldConfig::create([
      'field_storage' => $field_storage,
      'field_name' => 'field_text_position',
      'langcode' => \Drupal::languageManager()->getDefaultLanguage()->getId(),
      'entity_type' => 'paragraph',
      'bundle' => 'basic_hero',
      'translatable' => TRUE,
      'required' => TRUE,
      'label' => t('Text Position', [], ['langcode' => \Drupal::languageManager()->getDefaultLanguage()->getId()])
    ]);
    $field->save();

    // Assign widget settings for the 'default' form mode.
    $displayForm = $entityTypemanager
      ->getStorage('entity_form_display')
      ->load('paragraph' . '.' . 'basic_hero' . '.default');
    if ($displayForm) {
      $displayForm->setComponent('field_text_position', [
        'type' => 'styles'
      ]);
      $displayForm->save();
    }

    // Assign display settings for the 'default' and 'teaser' view modes.
    $displayDefault = $entityTypemanager
      ->getStorage('entity_view_display')
      ->load('paragraph' . '.' . 'basic_hero' . '.default');
    if ($displayDefault) {
      $displayDefault->removeComponent('field_text_position');
      $displayDefault->save();
    }

    // The teaser view mode is created by the Standard profile and therefore
    // might not exist.
    $viewModes = \Drupal::service('entity_display.repository')
      ->getViewModes('paragraph');
    if (isset($viewModes['teaser'])) {
      $displayTeaser = $entityTypemanager
        ->getStorage('entity_view_display')
        ->load('paragraph' . '.' . 'basic_hero' . '.teaser');
      if (!empty($displayTeaser)) {
        $displayTeaser->removeComponent('field_text_position');
        $displayTeaser->save();
      }
    }
  }

  $field = FieldConfig::loadByName('paragraph', 'inline_hero', 'field_text_position');
  if (empty($field)) {
    $field = FieldConfig::create([
      'field_storage' => $field_storage,
      'field_name' => 'field_text_position',
      'langcode' => \Drupal::languageManager()->getDefaultLanguage()->getId(),
      'entity_type' => 'paragraph',
      'bundle' => 'inline_hero',
      'translatable' => TRUE,
      'required' => TRUE,
      'label' => t('Text Position', [], ['langcode' => \Drupal::languageManager()->getDefaultLanguage()->getId()])
    ]);
    $field->save();

    // Assign widget settings for the 'default' form mode.
    $displayForm = $entityTypemanager
      ->getStorage('entity_form_display')
      ->load('paragraph' . '.' . 'inline_hero' . '.default');
    if ($displayForm) {
      $displayForm->setComponent('field_text_position', [
        'type' => 'styles'
      ]);
      $displayForm->save();
    }

    // Assign display settings for the 'default' and 'teaser' view modes.
    $displayDefault = $entityTypemanager
      ->getStorage('entity_view_display')
      ->load('paragraph' . '.' . 'inline_hero' . '.default');
    if ($displayDefault) {
      $displayDefault->removeComponent('field_text_position');
      $displayDefault->save();
    }

    // The teaser view mode is created by the Standard profile and therefore
    // might not exist.
    $viewModes = \Drupal::service('entity_display.repository')
      ->getViewModes('paragraph');
    if (isset($viewModes['teaser'])) {
      $displayTeaser = $entityTypemanager
        ->getStorage('entity_view_display')
        ->load('paragraph' . '.' . 'inline_hero' . '.teaser');
      if (!empty($displayTeaser)) {
        $displayTeaser->removeComponent('field_text_position');
        $displayTeaser->save();
      }
    }
  }

  $field = FieldConfig::loadByName('paragraph', 'basic_hero', 'field_color_theme');
  if (!empty($field)) {
    $field->delete();

    $field_storage = FieldStorageConfig::loadByName('paragraph', 'field_color_theme');
    if (!empty($field_storage)) {
      $field_storage->delete();
    }
    $field_storage = FieldStorageConfig::create([
      'field_name' => 'field_color_theme',
      'langcode' => \Drupal::languageManager()->getDefaultLanguage()->getId(),
      'entity_type' => 'paragraph',
      'type' => 'styles',
      'settings' => ['collection' => 'color_theme'],
      'module' => 'styles',
      'locked' => FALSE,
      'cardinality' => 1,
      'translatable' => TRUE
    ]);
    $field_storage->save();

    $field = FieldConfig::loadByName('paragraph', 'basic_hero', 'field_color_theme');
    if (empty($field)) {
      $field = FieldConfig::create([
        'field_storage' => $field_storage,
        'field_name' => 'field_color_theme',
        'langcode' => \Drupal::languageManager()->getDefaultLanguage()->getId(),
        'entity_type' => 'paragraph',
        'bundle' => 'basic_hero',
        'translatable' => TRUE,
        'required' => TRUE,
        'label' => t('Color theme', [], ['langcode' => \Drupal::languageManager()->getDefaultLanguage()->getId()])
      ]);
      $field->save();

      // Assign widget settings for the 'default' form mode.
      $displayForm = $entityTypemanager
        ->getStorage('entity_form_display')
        ->load('paragraph' . '.' . 'basic_hero' . '.default');
      if ($displayForm) {
        $displayForm->setComponent('field_color_theme', [
          'type' => 'styles'
        ]);
        $displayForm->save();
      }

      // Assign display settings for the 'default' and 'teaser' view modes.
      $displayDefault = $entityTypemanager
        ->getStorage('entity_view_display')
        ->load('paragraph' . '.' . 'basic_hero' . '.default');
      if ($displayDefault) {
        $displayDefault->removeComponent('field_color_theme');
        $displayDefault->save();
      }

      // The teaser view mode is created by the Standard profile and therefore
      // might not exist.
      $viewModes = \Drupal::service('entity_display.repository')
        ->getViewModes('paragraph');
      if (isset($viewModes['teaser'])) {
        $displayTeaser = $entityTypemanager
          ->getStorage('entity_view_display')
          ->load('paragraph' . '.' . 'basic_hero' . '.teaser');
        if (!empty($displayTeaser)) {
          $displayTeaser->removeComponent('field_color_theme');
          $displayTeaser->save();
        }
      }
    }
  }

  $paragraphs = Paragraph::loadMultiple($pids);
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  foreach ($paragraphs as $paragraph) {
    $value = $themes[$paragraph->id()];
    $value[0]['value'] = 'theme-' . $value[0]['value'];
    $paragraph->set('field_color_theme', $value);
    $value = $positions[$paragraph->id()];
    $value[0]['value'] = 'hero--' . $value[0]['value'];
    $paragraph->set('field_text_position', $value);
    $paragraph->save();
  }

  $paragraphs = Paragraph::loadMultiple($inner_pids);
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  foreach ($paragraphs as $paragraph) {
    $value = $inner_positions[$paragraph->id()];
    $value[0]['value'] = 'hero--' . $value[0]['value'];
    $paragraph->set('field_text_position', $value);
    $paragraph->save();
  }
  unset($paragraphs);

  $database = \Drupal::database();
  $results = $database->select('node__layout_builder__layout', 'nlbl')
    ->fields('nlbl', ['entity_id', 'deleted', 'delta', 'langcode', 'layout_builder__layout_section'])
    ->execute()
    ->fetchAll(PDO::FETCH_ASSOC);
  foreach ($results as $result) {
    /** @var Section $section */
    $section = unserialize($result['layout_builder__layout_section']);
    $settings = $section->getLayoutSettings();
    if ($settings['color_theme'] == 'none') {
      $settings['color_theme'] = '';
    } else {
      $settings['color_theme'] = 'theme-' . $settings['color_theme'];
    }
    $section->setLayoutSettings($settings);
    $database->update('node__layout_builder__layout')
      ->fields(['layout_builder__layout_section' => serialize($section)])
      ->condition('entity_id', $result['entity_id'])
      ->condition('deleted', $result['deleted'])
      ->condition('delta', $result['delta'])
      ->condition('langcode', $result['langcode'])
      ->execute();
  }
}
