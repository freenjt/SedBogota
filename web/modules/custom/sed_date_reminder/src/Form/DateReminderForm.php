<?php

namespace Drupal\sed_date_reminder\Form;

use Drupal\Core\Form\FormBase;
use \Drupal\Core\Routing;
use Drupal\Core\Form\FormStateInterface;

class DateReminderForm extends FormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'date_reminder_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $id_reminder = \Drupal::request()->query->get('id');
        if ($id_reminder) {
            $connection = \Drupal::database();
            $query = $connection->select('sed_date_reminder', 'sdr')
                ->fields('sdr')
                ->condition('id', $id_reminder)
                ->execute()
                ->fetchObject();
        }

        $form['id_reminder'] = [
            '#type' => 'value',
            '#value' => $id_reminder,
        ];

        $form['fieldset'] = [
            '#type' => 'fieldset',
            '#title' => $this->t('Date reminder'),
            '#collapsible' => FALSE,
            '#collapsed' => FALSE,
        ];
        $form['fieldset']['emails'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Emails'),
            '#description' => $this->t('Please write the emails separated for commas'),
            '#required' => TRUE,
            '#default_value' => isset($query->email) ? $query->email : NULL,
        ];
        $form['fieldset']['subject'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Subject'),
            '#required' => TRUE,
            '#default_value' => isset($query->subject) ? $query->subject : NULL,
        ];
        $form['fieldset']['message'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Message'),
            '#required' => TRUE,
            '#default_value' => isset($query->message) ? $query->message : NULL,
        ];
        $form['fieldset']['dates'] = [
            '#type' => 'container',
            '#title' => $this->t('reminder date'),
        ];
        $form['fieldset']['dates']['month'] = [
            '#type' => 'select',
            '#title' => $this->t('Month'),
            '#options' => range(1, 12),
            '#default_value' => isset($query->month) ? $query->month : NULL,
        ];
        $form['fieldset']['dates']['day'] = [
            '#type' => 'select',
            '#title' => $this->t('Day'),
            '#options' => range(1, 31),
            '#default_value' => isset($query->day) ? $query->day : NULL,
        ];
        $form['fieldset']['actions'] = [
            '#type' => 'actions',
        ];

        $form['fieldset']['actions']['save'] = [
            '#type' => 'submit',
            '#value' => $this->t('Save'),
        ];
        $form['fieldset']['actions']['cancel'] = [
            '#title' => $this->t('Cancel'),
            '#type' => 'link',
            '#url' => \Drupal\Core\Url::fromRoute('sed_date_reminder.display'),
        ];

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $id_reminder = $form_state->getValue('id_reminder');
        $connection = \Drupal::database();
        if ($id_reminder) {
            $result = $connection->update('sed_date_reminder')
                ->fields([
                    'email' => $form_state->getValue('emails'),
                    'subject' => $form_state->getValue('subject'),
                    'message' => $form_state->getValue('message'),
                    'month' => $form_state->getValue('month'),
                    'day' => $form_state->getValue('day'),
                ])->condition('id', $id_reminder)
                ->execute();
        } else {
            $result = $connection->insert('sed_date_reminder')
                ->fields([
                    'email' => $form_state->getValue('emails'),
                    'subject' => $form_state->getValue('subject'),
                    'message' => $form_state->getValue('message'),
                    'month' => $form_state->getValue('month'),
                    'day' => $form_state->getValue('day'),
                ])
                ->execute();
        }
        $form_state->setRedirect('sed_date_reminder.display');
        drupal_set_message($this->t('Your data have been saved'));
    }

}