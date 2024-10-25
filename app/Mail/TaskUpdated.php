<?php

namespace App\Mail;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TaskUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $task;

    /**
     * Create a new message instance.
     *
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.task_updated')
                    ->with([
                        'title' => $this->task->title,
                        'description' => $this->task->description,
                        'due_date' => $this->task->due_date,
                        'status' => $this->task->status,
                    ])
                    ->subject('Task Updated: ' . $this->task->title);
    }
}