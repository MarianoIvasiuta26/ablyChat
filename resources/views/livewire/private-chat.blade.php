<div
    class="flex h-[calc(100vh-12rem)] w-full max-w-6xl mx-auto bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-white/10 overflow-hidden">

    <!-- Sidebar: Users List -->
    <div class="w-1/3 border-r border-gray-200 dark:border-white/10 flex flex-col bg-gray-50/50 dark:bg-gray-900/50">
        <div class="p-4 border-b border-gray-200 dark:border-white/10">
            <h3 class="font-semibold text-gray-900 dark:text-white">Usuarios</h3>
        </div>

        <div class="flex-1 overflow-y-auto custom-scrollbar">
            @foreach($users as $user)
                <button wire:click="selectUser({{ $user->id }})"
                    class="w-full p-4 flex items-center gap-3 hover:bg-white dark:hover:bg-white/5 transition-colors border-b border-gray-100 dark:border-white/5 text-left {{ $selectedUserId === $user->id ? 'bg-white dark:bg-white/5 border-l-4 border-l-primary-600' : 'border-l-4 border-l-transparent' }}">

                    <div
                        class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                        <span class="text-gray-600 dark:text-gray-300 font-semibold text-sm">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                            {{ $user->name }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                            {{ $user->email }}
                        </p>
                    </div>
                </button>
            @endforeach
        </div>
    </div>

    <!-- Main Chat Area -->
    <div class="flex-1 flex flex-col bg-white dark:bg-gray-900" x-data="{ 
            scrollToBottom() { 
                this.$nextTick(() => { 
                    const el = this.$refs.messages; 
                    if(el) el.scrollTop = el.scrollHeight; 
                }) 
            } 
        }" @message-sent.window="scrollToBottom()">

        @if($activeConversation)
            <!-- Chat Header -->
            <div class="p-4 border-b border-gray-200 dark:border-white/10 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/50 flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold">
                        {{ strtoupper(substr($users->find($selectedUserId)->name, 0, 1)) }}
                    </div>
                    <span class="font-semibold text-gray-900 dark:text-white">
                        {{ $users->find($selectedUserId)->name }}
                    </span>
                </div>
            </div>

            <!-- Messages -->
            <div x-ref="messages" class="flex-1 overflow-y-auto p-6 space-y-6 custom-scrollbar"
                style="scrollbar-width: thin;">

                @forelse($messages as $m)
                    @php
                        $isMe = $m['user_id'] === auth()->id();
                    @endphp

                    <div class="flex w-full {{ $isMe ? 'justify-end' : 'justify-start' }}">
                        <div class="flex max-w-[75%] {{ $isMe ? 'flex-row-reverse' : 'flex-row' }} items-end gap-2">
                            <div class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }}">
                                <div class="px-4 py-2 rounded-2xl text-sm leading-relaxed shadow-sm border
                                                                    {{ $isMe
                    ? 'bg-primary-600 text-white border-primary-500 rounded-br-none'
                    : 'bg-gray-100 dark:bg-white/10 text-gray-800 dark:text-gray-200 border-gray-200 dark:border-white/10 rounded-bl-none' 
                                                                    }}">
                                    {{ $m['content'] }}
                                </div>
                                <span class="text-[10px] text-gray-400 mt-1 px-1">
                                    {{ $m['created_at'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-gray-400">
                        <p class="text-sm">No hay mensajes en esta conversación</p>
                    </div>
                @endforelse
            </div>

            <!-- Input -->
            <div class="p-4 border-t border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-gray-900/50">
                <form wire:submit="sendMessage" class="flex gap-3">
                    <input type="text" wire:model="content" placeholder="Escribe un mensaje..."
                        class="block w-full rounded-lg border-0 bg-white dark:bg-white/5 py-2.5 text-gray-950 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-white/10 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6"
                        autocomplete="off">

                    <button type="submit"
                        class="fi-btn fi-btn-size-md fi-btn-style-primary relative grid-flow-col items-center justify-center gap-1.5 rounded-lg px-4 py-2 text-sm font-semibold leading-6 outline-none transition duration-75 focus-visible:ring-2 bg-primary-600 hover:bg-primary-500 text-white shadow-sm flex-shrink-0">
                        <x-heroicon-m-paper-airplane class="w-5 h-5" />
                    </button>
                </form>
            </div>
        @else
            <div class="flex-1 flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                <x-heroicon-o-chat-bubble-left-right class="w-16 h-16 mb-4 opacity-50" />
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Mensajes Privados</h3>
                <p class="text-sm mt-2">Selecciona un usuario para comenzar a chatear</p>
            </div>
        @endif
    </div>
</div>