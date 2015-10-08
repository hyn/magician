<?php namespace Hyn\Teamspeak\Daemon\Presenters;

use Laracasts\Presenter\Presenter;

class ClientPresenter extends Presenter {

    public function nickname() {
        return array_get($this->entity->data, 'client_nickname');
    }

    public function country() {
        return array_get($this->entity->data, 'client_country');
    }
}