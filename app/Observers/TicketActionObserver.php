<?php

namespace App\Observers;

use App\Notifications\DataChangeEmailNotification;
use App\Notifications\AssignedTicketNotification;
use App\Notifications\UpdatedTicketNotification;
use App\Ticket;
use Illuminate\Support\Facades\Notification;

class TicketActionObserver
{
    public function created(Ticket $model)
    {
        $data  = ['action' => 'New ticket has been created!', 'model_name' => 'Ticket', 'ticket' => $model];
        $users = \App\User::whereHas('roles', function ($q) {
            return $q->where('title', 'Admin');
        })->get();
        Notification::send($users, new DataChangeEmailNotification($data));
        $user = $model->assigned_to_user;
        Notification::send($user, new DataChangeEmailNotification($data));
    }

    public function updated(Ticket $model)
    {   
        $user = $model->assigned_to_user;
        if($model->isDirty('assigned_to_user_id'))
        {
            if($user)
            {
                Notification::send($user, new AssignedTicketNotification($model));
            }
           
            
        }else{
        if($user)
        {
            Notification::send($user, new UpdatedTicketNotification($model));
        }}
}
}
