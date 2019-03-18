<?php

namespace App\Notifications;

use App\Models\BaseModel;
use App\Models\Process\ProcessInstance;
use App\Models\Process\ProcessNotification as ProcessNotificationModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProcessNotification extends Notification
{
    use Queueable;

    protected $entity;
    protected $model;
    protected $processInstance;

    /**
     * Create a new notification instance.
     *
     * @param  ProcessNotification $model
     * @param  ProcessInstance $processInstance
     * @return void
     */
    public function __construct(ProcessNotificationModel $model, ProcessInstance $processInstance)
    {
        $this->model = $model;
        $this->processInstance = $processInstance;
        $this->entity = entity($processInstance->entity_type, $processInstance->entity_id);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // Only send emails if configured to do so, otherwise default to database channel.
        return $this->processInstance->send_notifications ? ['mail', 'database'] : ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->model->subject_fr . ' / ' . $this->model->subject_en)
            ->markdown('emails.process_notification', [
                'entity'  => $this->entity,
                'content' => [
                    'en' => [
                        'body'       => $this->model->body_en,
                        'actionText' => 'Jump to Process',
                        'actionUrl'  => $this->resolveUrl('en'),
                    ],
                    'fr' => [
                        'body'       => $this->model->body_fr,
                        'actionText' => 'Aller au processus',
                        'actionUrl'  => $this->resolveUrl('fr'),
                    ],
                ],
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable = null)
    {
        return [
            'process_definition'  => $this->model->processDefinition->name_key,
            'email_key'           => $this->model->name_key,
            'process_instance_id' => $this->processInstance->id,
        ];
    }

    /**
     * Resolve action button url based on language and entity type.
     *
     * @param  string $lang
     * @return string
     */
    protected function resolveUrl($lang)
    {
        // Define entity url path based on entity model class.
        $parentClass = get_parent_class($this->entity);
        $entityClass = $parentClass == BaseModel::class ? class_basename($this->entity) : class_basename($parentClass);

        $replace = [
            ':app_url'             => config('app.url'),
            ':lang'                => $lang,
            ':entity_type'         => kebab_case(str_plural($entityClass)),
            ':entity_id'           => $this->entity->id,
            ':process_instance_id' => $this->processInstance->id,
        ];

        return str_placeholder($replace, ':app_url/:lang/:entity_type/:entity_id/process/:process_instance_id');
    }
}
