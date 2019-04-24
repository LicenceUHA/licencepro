<?php

namespace Drupal\hello\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Implements a hello form.
 */
class CalculatorForm extends FormBase{

  /**
  * {@inheritdoc}.
  */
  public function getFormID(){
    return 'hello_form';
  }

  /**
  * {@inheritdoc}.
  */
  public function buildForm(array $form, FormStateInterface $form_state){
    // champ destiné à afficher le resultat du calcul.
       
      /**$form['queued_items'] = array(
          '#type' => 'markup',
          '#markup' => $this->t('%number items waiting to be processed.', array('%number' => $queued_number)),
      ); */
      $form['first_value'] = [
        '#type' => 'textfield',
        '#title' => 'first value',
        '#required' => TRUE,
        '#ajax'  => [
          'callback' => [$this, 'AjaxValidateNumeric'],
          'event' => 'keyup',
        ],
        '#prefix' => '<span id="error-message-value1"></span>',
      ];
          // Bouton de selection + ..à.. /.  
      $form['operator'] = array(
          '#type' => 'radios',
          '#title' => $this->t('Operation'),
          '#description' => $this->t('choose operation for processing.'),
          '#options' => [
            'addition' => $this->t('add'),
            'soustraction' => $this->t('substract'),
            'multiplication' => $this->t('multiply'),
            'division' => $this->t('divide'),
          ],
          // selection + par defaut a l'affichage
          '#default_value' => 'addition',
          '#attributes' => ['class' => ['item-number']],
        );
          // Champ de saisie nombre 2
        $form['Second_value'] = [
          '#type' => 'textfield',
          '#title' => 'Second value',
          '#required' => TRUE,
          '#ajax' => [
            'callback' => [$this, 'AjaxValidateNumeric'],
            'event' => 'keyup',
          ],
          '#prefix' => '<span id="error-message-value2"></span>',
        ];
        // Bouton submit
        $form['actions']['submit'] = [
          '#type' => 'submit',
          '#value' => $this->t('Submit'),
        ];

        if (isset($form_state->getRebuildInfo()['result'])){
          $form['result'] = [
            '#markup' => '<h2>' . $this->t('RESULT: ') . $form_state->getRebuildInfo()['result'] . '</h2>',
          ];
        }

      return $form;
     
    }

    /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @return \Drupal\Core\Ajax\AjaxResponse
   */

  public function AjaxValidateNumeric(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $field = $form_state->getTriggeringElement()['#name'];
    if (is_numeric($form_state->getValue($field))) {
      $css = ['border' => '2px solid green'];
      $message = $this->t('OK!');
    } else {
      $css = ['border' => '2px solid red'];
      $message = $this->t('%field must be numeric!', ['%field' => $form[$field]['#title']]);
    }

    $response->AddCommand(new CssCommand("[name=$field]", $css));
    $response->AddCommand(new HtmlCommand('#error-message-' . $field, $message));
    return $response;
  }
  

  /**
  * {@inheritdoc}.
  */
    public function validateForm(array &$form, FormStateInterface $form_state) {

      $value_1 = $form_state->getValue('first_value');
      $value_2 = $form_state->getValue('Second_value');
      $operation = $form_state->getValue('operator');

      if (!is_numeric($value_1)){
        $form_state->setErrorByName('first_value', $this->t('Value 1 must be numeric'));
      }
      if ($value_2 == '0' && $operation == 'division') {

        $form_state->setErrorByName('Second_value', $this->t('Cannot divide by zero!'));
      }
      if (isset($form['result'])) {

       unset($form['result']);
 
      } 
  
    }

    /**
    * {@inheritdoc}.
    */
    public function submitForm(array &$form, FormStateInterface $form_state) {
      // Récupère la valeur des champs.    

      $value_1 = $form_state->getValue('first_value');
      $value_2 = $form_state->getValue('Second_value');
      $operation = $form_state->getValue('operator');

    
      //kint($form_state); pour afficher les messages
      // exit();

      $resultat ='';
      switch ($operation){
        case 'addition':
        $resultat = $value_1 + $value_2;
        break;
        case 'soustraction':
        $resultat = $value_1 - $value_2;
        break;
        case 'multiplication':
        $resultat = $value_1 * $value_2;
        break;
        case 'division':
        $resultat = $value_1 / $value_2;
        break;
      }
      $form_state->addRebuildInfo('result', $resultat);
      // reconstitution formulaire
      $form_state->setRebuild();

      // \Drupal::messenger()->addMessage($resultat);
    }
  


}