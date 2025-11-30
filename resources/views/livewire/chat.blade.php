<div class="flex flex-col h-[500px] max-h-[calc(100vh-10rem)] w-full max-w-4xl mx-auto bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-white/10 overflow-hidden"
    x-data="{ 
        scrollToBottom() { 
            this.$nextTick(() => { 
                const el = this.$refs.messages; 
                if(el) el.scrollTop = el.scrollHeight; 
            }) 
        } 
    }" x-init="scrollToBottom()" @message-sent.window="scrollToBottom()">

    <!-- Messages Area -->
    <div x-ref="messages" id="messages"
        class="flex-1 overflow-y-auto p-6 space-y-10 custom-scrollbar border-b border-gray-200 dark:border-white/10"
        style="scrollbar-width: auto; scrollbar-color: #cbd5e1 transparent;">

        @forelse($messages as $index => $m)
            @php
                $isMe = $m['user_id'] === auth()->id();
            @endphp

            <div class="flex w-full {{ $isMe ? 'justify-end' : 'justify-start' }} animate-fade-in group"
                style="animation-delay: {{ $index * 0.02 }}s">

                <div class="flex max-w-[80%] {{ $isMe ? 'flex-row-reverse' : 'flex-row' }} items-end gap-3">
                    <!-- Avatar -->
                    <div
                        class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 ring-2 ring-white dark:ring-gray-800 shadow-sm {{ $isMe ? 'bg-primary-600' : 'bg-gray-500' }}">
                        <span class="text-white font-bold text-xs">
                            {{ strtoupper(substr($m['username'], 0, 1)) }}
                        </span>
                    </div>

                    <!-- Message Bubble -->
                    <div class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }}">
                        <div
                            class="flex items-baseline gap-2 mb-1.5 px-1 opacity-80 group-hover:opacity-100 transition-opacity">
                            <span
                                class="text-xs font-semibold {{ $isMe ? 'text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300' }}">
                                {{ $m['username'] }}
                            </span>
                            <span class="text-[10px] text-gray-400">
                                {{ $m['created_at'] ?? 'ahora' }}
                            </span>
                        </div>

                        <div class="px-5 py-3 rounded-2xl shadow-sm text-sm leading-relaxed break-words border
                                        {{ $isMe
            ? 'bg-primary-600 text-white border-primary-500 rounded-br-none'
            : 'bg-white dark:bg-white/5 text-gray-800 dark:text-gray-200 border-gray-200 dark:border-white/10 rounded-bl-none' 
                                        }}">
                            {{ $m['content'] }}
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center h-full text-gray-400 dark:text-gray-500">
                <div class="p-4 rounded-full bg-gray-50 dark:bg-white/5 mb-4">
                    <x-heroicon-o-chat-bubble-left-right class="w-8 h-8 opacity-50" />
                </div>
                <p class="text-sm font-medium">No hay mensajes aún</p>
                <p class="text-xs mt-1 opacity-70">¡Sé el primero en enviar un mensaje!</p>
            </div>
        @endforelse
    </div>

    <!-- Input Area -->
    <div class="p-6 bg-gray-50/50 dark:bg-gray-900/50 flex-shrink-0 z-10">
        <form wire:submit="sendMessage" class="flex gap-3">
            <input type="text" wire:model="content" placeholder="Escribe un mensaje..."
                class="block w-full rounded-lg border-0 bg-white dark:bg-white/5 py-2.5 text-gray-950 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-white/10 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6"
                autocomplete="off">

            <button type="submit"
                class="fi-btn fi-btn-size-md fi-btn-style-primary relative grid-flow-col items-center justify-center gap-1.5 rounded-lg px-4 py-2 text-sm font-semibold leading-6 outline-none transition duration-75 focus-visible:ring-2 bg-primary-600 hover:bg-primary-500 text-white shadow-sm flex-shrink-0">
                <span class="hidden sm:inline">Enviar</span>
                <x-heroicon-m-paper-airplane class="w-5 h-5" />
            </button>
        </form>
    </div>
</div>