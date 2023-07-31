import axios from 'axios'

export const ffCheckout = (data) => {
    return axios.post('/api/user/ff/checkout', data);
};

export const checkout = (merchantSessionKey, cardIdentifier, data, isFF) => {
    return axios.post('/api/user/checkout', {
        ...data,
        'card_identifier': cardIdentifier,
        'merchant_session_key': merchantSessionKey,
        'isFF' : isFF,
    });
};
