<button id="chat-widget-trigger" onclick="toggleChatWindow()" style="position: fixed; bottom: 20px; right: 20px; width: 60px; height: 60px; background: #007bff; color: white; border: none; border-radius: 50%; cursor: pointer; box-shadow: 0 4px 15px rgba(0,123,255,0.3); z-index: 9999; font-size: 28px; display: flex; align-items: center; justify-content: center; transition: transform 0.2s ease;">
  🦷
</button>

<div id="chat-widget-window" style="position: fixed; bottom: 95px; right: 20px; width: 360px; height: 480px; background: white; border-radius: 16px; box-shadow: 0 8px 30px rgba(0,0,0,0.15); z-index: 9999; display: none; flex-direction: column; overflow: hidden; font-family: system-ui, -apple-system, sans-serif;">
  <div style="background: #007bff; color: white; padding: 15px; display: flex; align-items: center; justify-content: space-between;">
    <div style="display: flex; align-items: center; gap: 10px;">
      <span style="font-size: 22px;">🦷</span>
      <div>
        <h4 style="margin: 0; font-size: 15px; font-weight: 600;">ToothBuddy AI</h4>
        <p style="margin: 0; font-size: 11px; opacity: 0.8;">Online Clinic Assistant</p>
      </div>
    </div>
    <button onclick="toggleChatWindow()" style="background: none; border: none; color: white; font-size: 20px; cursor: pointer;">&times;</button>
  </div>
  
  <div id="chat-messages-container" style="flex: 1; padding: 15px; overflow-y: auto; background: #f8f9fa; display: flex; flex-direction: column; gap: 12px;">
    <div style="align-self: flex-start; background: white; color: #333; padding: 10px 14px; border-radius: 12px 12px 12px 4px; max-width: 80%; font-size: 13.5px; border: 1px solid #eef0f2;">
      Hello! I'm ToothBuddy. Ask me about Bag-Ang Dental Clinic's services, operational hours, or locations! 😊
    </div>
  </div>
  
  <div id="chat-typing-indicator" style="display: none; padding: 10px 15px; background: #f8f9fa; font-size: 12px; color: #666; font-style: italic;">
    ⚡ ToothBuddy is typing...
  </div>
  
  <div style="padding: 12px; background: white; border-top: 1px solid #eee; display: flex; gap: 8px; align-items: center;">
    <input type="text" id="widget-user-input" placeholder="Type a message..." onkeypress="handleWidgetKeyPress(event)" style="flex: 1; padding: 10px 14px; border: 1px solid #dee2e6; border-radius: 20px; font-size: 13.5px; outline: none; background: #f8f9fa;">
    <button onclick="sendWidgetMessage()" style="background: #007bff; color: white; border: none; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center;">➔</button>
  </div>
</div>

<script>
function toggleChatWindow() {
  const windowBox = document.getElementById('chat-widget-window');
  const triggerBtn = document.getElementById('chat-widget-trigger');
  if (windowBox.style.display === 'none' || windowBox.style.display === '') {
    windowBox.style.display = 'flex';
    triggerBtn.style.transform = 'scale(0.9) rotate(90deg)';
    document.getElementById('widget-user-input').focus();
  } else {
    windowBox.style.display = 'none';
    triggerBtn.style.transform = 'scale(1) rotate(0deg)';
  }
}

function handleWidgetKeyPress(event) { 
  if (event.key === 'Enter') { sendWidgetMessage(); } 
}

async function sendWidgetMessage() {
  const inputEl = document.getElementById('widget-user-input');
  const msgContainer = document.getElementById('chat-messages-container');
  const typingIndicator = document.getElementById('chat-typing-indicator');
  const userText = inputEl.value.trim();
  if (!userText) return;

  inputEl.value = '';

  const userBubble = document.createElement('div');
  userBubble.style = "align-self: flex-end; background: #007bff; color: white; padding: 10px 14px; border-radius: 12px 12px 4px 12px; max-width: 80%; font-size: 13.5px;";
  userBubble.innerText = userText;
  msgContainer.appendChild(userBubble);
  msgContainer.scrollTop = msgContainer.scrollHeight;
  typingIndicator.style.display = 'block';

  try {
    let response = await fetch('/ask-ai', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ message: userText })
    });
    let data = await response.json();
    typingIndicator.style.display = 'none';

    const aiBubble = document.createElement('div');
    aiBubble.style = "align-self: flex-start; background: white; color: #333; padding: 10px 14px; border-radius: 12px 12px 12px 4px; max-width: 80%; font-size: 13.5px; border: 1px solid #eef0f2;";
    aiBubble.innerText = data.reply;
    msgContainer.appendChild(aiBubble);
    msgContainer.scrollTop = msgContainer.scrollHeight;
  } catch (error) {
    typingIndicator.style.display = 'none';
    const errBubble = document.createElement('div');
    errBubble.style = "align-self: flex-start; background: #f8d7da; color: #721c24; padding: 10px 14px; border-radius: 12px; max-width: 80%; font-size: 13.5px;";
    errBubble.innerText = "System Network Error: Unable to reach AI assistant.";
    msgContainer.appendChild(errBubble);
  }
}
</script>