<?php

    use App\Models\User;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Broadcast;
    use Illuminate\Support\Facades\Log;

    Broadcast::channel('recipe', function () {
        return Auth::check();
});

