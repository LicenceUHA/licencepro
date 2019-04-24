<?php 
namespace Drupal\hello\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * implements an admin form
 */

 class Hellostatconfig extends ConfigFormBase{

    /**
     * {@inheritdoc}.
     */
    public function getFormId(){
        return 'resetstatuser_form';
    }

    /**
     * {@inheritdoc}.
     */

    protected function getEditableConfigNames(){
        return ['hello.settings'];
    }

    /**
     * {@inheritdoc}.
     */

    public function buildForm(array $form, FormStateInterface $form_state){
        $delay = $this->config('hello.settings')->get('delay');
        $form['delay'] = array(
            '#type' => 'select',
            '#title' => $this->t('how long to keep user statistics'),
            '#options' => [
              '0' => $this->t('Pas de purge'),
              '1' => $this->t('1 day'),
              '2' => $this->t('2 day'),
              '7' => $this->t('7 day'),
              '14' => $this->t('15 jours'),
              '30' => $this->t('1 mois'),
              '60' => $this->t('2 mois'),
            ],
            // selection + par defaut a l'affichage
            '#default_value' => $delay,
            
          );
        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}.
     */

    public function submitForm(array &$form, FormStateInterface $form_state){
        parent::submitForm($form, $form_state);

        $value = $form_state->getValue('delay');
  
        $this->config('hello.settings')->set('delay', $value)->save();
        
    }
 }
?>