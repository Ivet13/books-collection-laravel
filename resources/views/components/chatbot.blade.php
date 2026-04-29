<div class="chatbot">
    <button>Ayudante IA</button>
    <div class="messages d-none">
        <div class="chat-content" id="bot-message">
            <p>Hola, como puedo ayudarte?</p>
        </div>
        <div class="user-message">
            <input class="user-message-content" placeholder="Escribe tu mensaje"</input>
            <div class="sendUserMessage"><x-icons.send /></div>
        </div>
    </div>
</div>

</div>


<style>
    .chatbot {
        position: fixed;
        right: 0;
        bottom: 30px;
        background-color: var(--panel-bg-color);

    }

    .messages {
        min-height: 200px;
        min-width: 400px;
    }

    .user-message {
        width: 100%;
        display: flex;
        justify-content: space-between;
        position: absolute;
        bottom: 0;
    }

    .d-none {
        display: none;
    }
</style>
