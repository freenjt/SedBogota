<?php

namespace Drupal\sed_date_reminder\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

class DateReminderList extends ControllerBase
{
    function displayTable()
    {
        $header_table = array(
            'email' => t('Email'),
            'subject' => t('Subject'),
            'message' => t('Message'),
            'date' => t('Notification date'),
            'op' => t('Edit'),
            'op2' => t('Delete'),
        );
//select records from table
        $query = \Drupal::database()->select('sed_date_reminder', 'sdr');
        $query->fields('sdr');
        $results = $query->execute()->fetchAll();
        $rows = array();
        foreach ($results as $data) {
            $edit = Url::fromUserInput('/date-reminder/manage?id=' . $data->id);
            $delete = Url::fromUserInput('/date-reminder/delete/' . $data->id);
            //print the data from table
            $notification_date = date('Y') . '-' . ($data->month + 1) . '-' . ($data->day + 1);
            $notification_time = strtotime($notification_date);

            $notification = format_date($notification_time, 'custom', 'F j');
            $rows[] = array(
                'email' => $data->email,
                'subject' => $data->subject,
                'name' => $data->message,
                'date' => $notification,
                \Drupal::l('Edit', $edit),
                \Drupal::l('Delete', $delete),
            );
        }
        //display data in site
        $build['table'] = [
            '#type' => 'table',
            '#header' => $header_table,
            '#rows' => $rows,
            '#empty' => t('No data found'),
        ];
        return $build;
    }
}