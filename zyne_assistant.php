<div id="zyneChatWidget" class="zyne-chat-widget">
    <div class="zyne-header">
        <div class="zyne-profile">
            <div class="zyne-avatar">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
            </div>
            <div class="zyne-info">
                <h4>Zyne AI</h4>
                <p>Always ready to help</p>
            </div>
        </div>
        <div class="zyne-actions">
            <button class="zyne-action-btn" onclick="clearZyneChat()" title="Clear Chat">
                <i class="fa-solid fa-trash-can"></i>
            </button>
            <button class="zyne-close-btn" onclick="closeZyneAssistant()" title="Close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>

    <div class="zyne-body" id="zyneChatBody">
        <div class="zyne-message bot">
            <div class="msg-bubble">
                Hi! I am Zyne, your personal AI guide. 🤖<br>What do you want to learn or solve today?
            </div>
        </div>
    </div>

    <div class="zyne-footer">
        <input type="text" id="zyneInput" placeholder="Ask Zyne anything..." onkeypress="handleZyneEnter(event)">
        <button class="zyne-send-btn" onclick="sendZyneMessage()" id="zyneSendBtn">
            <i class="fa-solid fa-paper-plane"></i>
        </button>
    </div>
</div>

<style>
/* ==========================================
   🤖 ZYNE AI CHAT STYLES (Upgraded)
   ========================================== */
.zyne-chat-widget {
    position: fixed; bottom: 30px; right: 85px; width: 380px; height: 520px;
    background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(25px); -webkit-backdrop-filter: blur(25px);
    border: 1px solid rgba(226, 232, 240, 0.8); border-radius: 28px;
    box-shadow: 0 35px 70px rgba(15, 23, 42, 0.15), 0 0 0 1px rgba(255,255,255,0.6);
    display: flex; flex-direction: column; z-index: 99999;
    opacity: 0; pointer-events: none; transform: translateY(40px) scale(0.95);
    transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.zyne-chat-widget.active { opacity: 1; pointer-events: auto; transform: translateY(0) scale(1); }

.zyne-header {
    padding: 18px 24px; background: linear-gradient(135deg, #4F46E5, #06B6D4);
    border-radius: 28px 28px 0 0; display: flex; justify-content: space-between;
    align-items: center; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.zyne-profile { display: flex; align-items: center; gap: 14px; }
.zyne-avatar {
    width: 44px; height: 44px; background: rgba(255, 255, 255, 0.25); border-radius: 50%;
    display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1); border: 1px solid rgba(255,255,255,0.4);
}
.zyne-info h4 { margin: 0; font-size: 1.15rem; font-weight: 700; font-family: 'Plus Jakarta Sans', sans-serif;}
.zyne-info p { margin: 0; font-size: 0.85rem; opacity: 0.9; }

.zyne-actions { display: flex; gap: 12px; }
.zyne-action-btn, .zyne-close-btn {
    background: rgba(255,255,255,0.1); border: none; color: white;
    width: 32px; height: 32px; border-radius: 50%; cursor: pointer; transition: 0.3s;
    display: flex; align-items: center; justify-content: center; font-size: 1rem;
}
.zyne-action-btn:hover { background: rgba(255,255,255,0.3); }
.zyne-close-btn:hover { background: #EF4444; transform: rotate(90deg); }

.zyne-body {
    flex-grow: 1; padding: 24px; overflow-y: auto; display: flex; flex-direction: column; gap: 18px;
    background: #F8FAFC; /* Soft Light Background */
}
.zyne-body::-webkit-scrollbar { width: 6px; }
.zyne-body::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }

.zyne-message { display: flex; max-width: 88%; animation: fadeIn 0.3s ease; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

.zyne-message.bot { align-self: flex-start; }
.zyne-message.user { align-self: flex-end; }

.msg-bubble {
    padding: 14px 18px; border-radius: 20px; font-size: 0.95rem; line-height: 1.6;
    box-shadow: 0 4px 15px rgba(0,0,0,0.04); word-wrap: break-word;
}
.zyne-message.bot .msg-bubble { background: #ffffff; color: #1E293B; border-bottom-left-radius: 4px; border: 1px solid #E2E8F0; }
.zyne-message.user .msg-bubble { background: linear-gradient(135deg, #4F46E5, #3B82F6); color: white; border-bottom-right-radius: 4px; }

.zyne-footer { padding: 18px; border-top: 1px solid #E2E8F0; display: flex; gap: 12px; background: #ffffff; border-radius: 0 0 28px 28px; }
#zyneInput {
    flex-grow: 1; border: 1px solid #CBD5E1; border-radius: 100px; padding: 12px 18px;
    outline: none; transition: 0.3s; font-size: 0.95rem; background: #F8FAFC;
}
#zyneInput:focus { border-color: #4F46E5; background: #ffffff; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }

.zyne-send-btn {
    width: 46px; height: 46px; border-radius: 50%; border: none; background: #4F46E5; color: white;
    cursor: pointer; transition: 0.3s; box-shadow: 0 6px 15px rgba(79, 70, 229, 0.3);
    display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;
}
.zyne-send-btn:hover { background: #06B6D4; transform: scale(1.08) translateY(-2px); box-shadow: 0 8px 20px rgba(6, 182, 212, 0.4); }
.zyne-send-btn:disabled { background: #94A3B8; cursor: not-allowed; transform: none; box-shadow: none; }

.typing-dots span {
    display: inline-block; width: 6px; height: 6px; background: #64748B;
    border-radius: 50%; margin: 0 2px; animation: typing 1.4s infinite ease-in-out both;
}
.typing-dots span:nth-child(1) { animation-delay: -0.32s; }
.typing-dots span:nth-child(2) { animation-delay: -0.16s; }
@keyframes typing { 0%, 80%, 100% { transform: scale(0); } 40% { transform: scale(1); } }
</style>

<script>
// ==========================================
// 🤖 DYNAMIC ZYNE AI CHAT LOGIC (Upgraded)
// ==========================================

// 🌟 New Feature: Chat History Array to remember context
let zyneChatHistory = []; 

function openZyneAssistant() {
    const widget = document.getElementById('zyneChatWidget');
    if(widget) {
        widget.classList.add('active');
        setTimeout(() => { document.getElementById('zyneInput').focus(); }, 300);
    }
}

function closeZyneAssistant() {
    const widget = document.getElementById('zyneChatWidget');
    if(widget) { widget.classList.remove('active'); }
}

// 🌟 New Feature: Clear Chat History
function clearZyneChat() {
    zyneChatHistory = []; // Reset memory
    const chatBody = document.getElementById('zyneChatBody');
    chatBody.innerHTML = `
        <div class="zyne-message bot">
            <div class="msg-bubble">
                Chat cleared! Let's start fresh. 🧹<br>What do you want to learn today?
            </div>
        </div>
    `;
}

function handleZyneEnter(event) {
    if (event.key === 'Enter') {
        event.preventDefault(); 
        sendZyneMessage();
    }
}

async function sendZyneMessage() {
    const inputField = document.getElementById('zyneInput');
    const sendBtn = document.getElementById('zyneSendBtn');
    const message = inputField.value.trim();
    const chatBody = document.getElementById('zyneChatBody');

    if (message === "") return;

    // Disable input while processing
    inputField.disabled = true;
    sendBtn.disabled = true;

    // Show User Message
    chatBody.innerHTML += `<div class="zyne-message user"><div class="msg-bubble">${message}</div></div>`;
    inputField.value = ""; 
    chatBody.scrollTo({ top: chatBody.scrollHeight, behavior: 'smooth' }); // Smart smooth scroll

    // Show AI Typing
    const typingId = "typing-" + Date.now();
    chatBody.innerHTML += `
        <div class="zyne-message bot" id="${typingId}">
            <div class="msg-bubble typing-dots">
                <span></span><span></span><span></span>
            </div>
        </div>
    `;
    chatBody.scrollTo({ top: chatBody.scrollHeight, behavior: 'smooth' });

    try {
        const response = await fetch('zyne_chat_api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                message: message,
                history: zyneChatHistory // 🌟 Sending history to backend
            })
        });

        const data = await response.json();
        document.getElementById(typingId).remove();

        // 🌟 Basic Markdown to HTML (Bold & Line breaks)
        let formattedReply = data.reply.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        
        chatBody.innerHTML += `<div class="zyne-message bot"><div class="msg-bubble">${formattedReply}</div></div>`;
        
        // Save to history
        zyneChatHistory.push({ role: "user", parts: [{ text: message }] });
        zyneChatHistory.push({ role: "model", parts: [{ text: data.reply }] });

    } catch (error) {
        document.getElementById(typingId).remove();
        chatBody.innerHTML += `<div class="zyne-message bot"><div class="msg-bubble" style="color: #EF4444;">Connection error. Try again!</div></div>`;
    }

    // Re-enable input
    inputField.disabled = false;
    sendBtn.disabled = false;
    inputField.focus();
    chatBody.scrollTo({ top: chatBody.scrollHeight, behavior: 'smooth' });
}
</script>