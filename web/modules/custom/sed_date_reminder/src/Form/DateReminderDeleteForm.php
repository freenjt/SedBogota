<?php
/**
 * Created by PhpStorm.
 * User: macbookpro
 * Date: 2019-02-19
 * Time: 13:49
 */

namespace Drupal\sed_date_reminder\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Render\Element;

class DateReminderDeleteForm extends ConfirmFormBase
{

    /**
     * Returns the question to ask the user.
     *
     * @return \Drupal\Core\StringTranslation\TranslatableMarkup
     *   The form question. The page title will be set to this value.
     */
    public function getQuestion()
    {
        $query = \Drupal::database();
        $subject = $query->select('sed_date_reminder', 'sdr')
            ->fields('sdr', ['subject'])
            ->condition('id', $this->id)
            ->execute()
            ->fetchField();
        return t('Do you want to delete %subject?', array('%subject' => $subject));
    }

    /**
     * Returns the route to go to if the user cancels the action.
     *
     * @return \Drupal\Core\Url
     *   A URL object.
     */
    public function getCancelUrl()
    {
        return new Url('sed_date_reminder.display');
    }

    /**
     * Returns a unique string identifying the form.
     *
     * The returned ID should be a unique string that can be a valid PHP function
     * name, since it's used in hook implementation names such as
     * hook_form_FORM_ID_alter().
     *
     * @return string
     *   The unique string identifying the form.
     */
    public function getFormId()
    {
        return 'date_reminder_delete_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, $id = NULL)
    {
        $this->id = $id;
        return parent::buildForm($form, $form_state);
    }

    /**
     * Form submission handler.
     *
     * @param array $form
     *   An associative array containing the structure of the form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   The current state of the form.
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $query = \Drupal::database();
        $query->delete('sed_date_reminder')
            ->condition('id',$this->id)
            ->execute();
        drupal_set_message("succesfully deleted");
        $form_state->setRedirect('sed_date_reminder.display');
    }
}