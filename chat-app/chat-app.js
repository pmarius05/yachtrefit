Vue.component('message-board', {
    data() {
        return {
            messages: [
                { text: 'Hello', user: 'Alice' },
                { text: 'Hi', user: 'Bob' },
            ],
            newMessage: ''
        };
    },

    template: `
        <div xmlns="http://www.w3.org/1999/html">
            <div v-for="message in messages" :key="message.text">
                <strong>{{message.user}}: </strong> {{message.text}}
            </div>
            <input v-model="newMessage" @keyup.enter="sendMessage" placeholder="Type a message...">
        </div>
    `,
    methods: {
        sendMessage() {
            if (this.newMessage.trim() === '') return;

            const messageData = {
                user: 'John', //replace with dynamic user
                message: this.newMessage.trim()
            };

            this.messages.push(messageData);

            fetch('http://localhost/yachtrefitnew/ins1/wp-json/chat/v1/message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(messageData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.code && data.code === 'cant-save') {
                        //Error
                        console.log('String error');
                    }else {
                        //Success
                        console.log('String success');
                        console.log(data.code);
                    }
                })
                .catch((error) => console.error('Fetch Error', error));

            // Reset the input field
            this.newMessage = '';
        }
    }
});

new Vue({
    el: '#app'
});