
import { atom } from 'nanostores';


export const appointment = atom({
    id: "",
    name: "",
    mob: "",
    date: "",
    doctor: "",
    department: ""
});


export const appointments = atom([]);
