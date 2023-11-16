require('./bootstrap');
require('./main')

import Alpine from 'alpinejs'

// chat start
import {createApp} from 'vue';
import ChatMessages from './components/ChatMessages';
import ChatForm from './components/ChatForm';

const app = createApp({
    components: {
        ChatMessages,
        ChatForm
    }
})
app.mount('#app')
// chat end

window.Alpine = Alpine

document.addEventListener('DOMContentLoaded', () => {
    Alpine.start()
})
