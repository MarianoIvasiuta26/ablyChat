<div>
    <h1>EventChat</h1>

    <div style="margin-bottom:10px">
        <label>Tu nombre:</label>
        <input type="text" wire:model="username">
    </div>

    <div id="messages"
        style="border: 1px solid #ccc; padding: 10px; height: 300px; overflow-y: auto; margin-bottom: 10px;">
        @foreach($messages as $m)
            <div class="msg" style="margin-bottom: 5px;">
                <span style="font-weight: bold; margin-right: 5px;">{{ $m['username'] }}:</span> {{ $m['content'] }}
            </div>
        @endforeach
    </div>

    <form wire:submit="sendMessage">
        <input type="text" wire:model="content" placeholder="Escribe un mensaje..." style="width:80%">
        <button type="submit">Enviar</button>
    </form>
</div>