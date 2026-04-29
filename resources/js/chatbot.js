document.addEventListener("DOMContentLoaded", () => {

    const chatBox = document.getElementById("bot-message");
    const input = document.querySelector(".chatbot input");

    // backend call
    async function sendMessage(message) {
        const res = await fetch('/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ message })
        });

        const data = await res.json();
        return data.reply;
    }

    // sender logic
    async function handleSend() {
        const message = input.value.trim();
        if (!message) return;

        addMessage(message, 'user');
        input.value = "";

        // Loader
        const loader = addMessage('...', 'bot');

        try {
            const reply = await sendMessage(message);

            loader.remove();
            addMessage(reply, 'bot');

        } catch (err) {
            loader.remove();
            addMessage("Error al enviar mensaje", 'bot');
            console.error(err);
        }
    }

    // global clicks
    document.addEventListener("click", (e) => {

        const toggleBtn = e.target.closest(".chatbot > button");
        const chatbotBox = e.target.closest(".chatbot");
        const sendBtn = e.target.closest(".sendUserMessage");

        // open/close chatbot
        if (toggleBtn) {
            document.querySelector(".messages").classList.toggle("d-none");
            return;
        }

        // close if click 
        if (!chatbotBox) {
            document.querySelector(".messages").classList.add("d-none");
            return;
        }

        // send button
        if (sendBtn) {
            handleSend();
        }
    });

    // send on enter
    input.addEventListener("keydown", (e) => {
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault();
            handleSend();
        }
    });

    // add message to chat
    function addMessage(text, type) {
        const p = document.createElement('p');
        p.textContent = text;

        if (type === 'user') {
            p.style.background = '#4f46e5';
            p.style.color = 'white';
            p.style.marginLeft = 'auto';
        }

        chatBox.appendChild(p);

        // automatic scroll
        chatBox.scrollTop = chatBox.scrollHeight;

        return p;
    }
});