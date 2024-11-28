import { useState, useEffect } from 'react';

const AppointmentForm = () => {
    const [appointment, setAppointment] = useState({
        id: "",
        name: "",
        mob: "",
        date: "",
        doctor: "",
        department: ""
    });
    const [appointments, setAppointments] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    // Fetch existing appointments from the backend
    useEffect(() => {
        const fetchAppointments = async () => {
            try {
                const response = await fetch('http://localhost/Pure-PHP-CRUD-Project-main/api/index.php');
                const data = await response.json();
                setAppointments(data); 
                setLoading(false);
            } catch (err) {
                setError("Failed to fetch appointments");
                setLoading(false);
            }
        };

        fetchAppointments();
    }, []); // Runs once when the component mounts

    const createOrUpdateAppointment = async (e) => {
        e.preventDefault();

        const url = "http://localhost/Pure-PHP-CRUD-Project-main/api/index.php";
        const method = appointment.id ? "PUT" : "POST"; 

        const body = JSON.stringify(appointment);

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    "Content-Type": "application/json"
                },
                body: body
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || "Failed to process request.");
            }

            // Refresh appointments after successful request
            const updatedAppointments = await fetchAppointmentsFromAPI();
            setAppointments(updatedAppointments); 
            // Reset form after submission
            setAppointment({
                id: "",
                name: "",
                mob: "",
                date: "",
                doctor: "",
                department: ""
            });

            console.log(result); 

        } catch (error) {
            console.error("Error:", error.message);
        }
    };

    // Fetch appointments data from API
    const fetchAppointmentsFromAPI = async () => {
        const response = await fetch('http://localhost/Pure-PHP-CRUD-Project-main/api/index.php');
        const data = await response.json();
        return data; 
    };

    const handleChange = (e) => {
        setAppointment({
            ...appointment,
            [e.target.name]: e.target.value
        });
    };

    if (loading) {
        return <div>Loading...</div>;
    }

    if (error) {
        return <div>{error}</div>;
    }

    return (
        <>

<div className="flex flex-col items-start justify-between h-screen px-6 py-12">
    <h1 className="text-5xl text-green mb-12">Appointment Form</h1>

    <form onSubmit={createOrUpdateAppointment} className="w-full max-w-lg">
        <div className="flex flex-wrap -mx-3 mb-6">
            <div className="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label className="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" htmlFor="grid-name">
                    Name
                </label>
                <input
                    name="name"
                    onChange={handleChange}
                    value={appointment.name}
                    className="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                    id="grid-name"
                    type="text"
                    placeholder="John Doe"
                />
            </div>
            <div className="w-full md:w-1/2 px-3">
                <label className="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" htmlFor="grid-mob">
                    Mobile
                </label>
                <input
                    name="mob"
                    onChange={handleChange}
                    value={appointment.mob}
                    className="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                    id="grid-mob"
                    type="text"
                    placeholder="123-456-7890"
                />
            </div>
        </div>
        <div className="flex flex-wrap -mx-3 mb-6">
            <div className="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label className="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" htmlFor="grid-date">
                    Date
                </label>
                <input
                    name="date"
                    onChange={handleChange}
                    value={appointment.date}
                    className="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                    id="grid-date"
                    type="date"
                />
            </div>
            <div className="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label className="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" htmlFor="grid-doctor">
                    Doctor
                </label>
                <input
                    name="doctor"
                    onChange={handleChange}
                    value={appointment.doctor}
                    className="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                    id="grid-doctor"
                    type="text"
                    placeholder="Dr. Smith"
                />
            </div>
        </div>
        <div className="flex flex-wrap -mx-3 mb-6">
            <div className="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label className="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" htmlFor="grid-department">
                    Department
                </label>
                <input
                    name="department"
                    onChange={handleChange}
                    value={appointment.department}
                    className="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                    id="grid-department"
                    type="text"
                    placeholder="Cardiology"
                />
            </div>
        </div>
        <button type="submit" className="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
            Submit
        </button>
    </form>
</div>

        </>
    );
}

export default AppointmentForm;
