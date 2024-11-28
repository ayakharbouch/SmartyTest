import { atom } from "nanostores";
import { useEffect, useState } from "react";
import { useStore } from "@nanostores/react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCheck, faEdit, faTrash } from "@fortawesome/free-solid-svg-icons";

// Create an atom to hold appointments
export const appointments = atom([]);

const AppointmentsTable = () => {
  const $appointments = useStore(appointments); 
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [searchTerm, setSearchTerm] = useState("");
  const [editId, setEditId] = useState(null); 
  const [editedData, setEditedData] = useState({}); 

  // Fetch data from the API
  useEffect(() => {
    const fetchAppointments = async () => {
      try {
        const response = await fetch(
          "http://localhost/Pure-PHP-CRUD-Project-main/api/index.php"
        );

        if (!response.ok) {
          throw new Error("Failed to fetch appointments.");
        }

        const data = await response.json();

        if (Array.isArray(data)) {
          const uniqueAppointments = [
            ...new Map(data.map((item) => [item.id, item])).values(),
          ];
          appointments.set(uniqueAppointments);
        } else {
          throw new Error("Invalid data format from the server.");
        }

        setLoading(false);
      } catch (error) {
        setError(error.message);
        setLoading(false);
      }
    };

    fetchAppointments();
  }, []); 

  const handleSearch = (value) => {
    setSearchTerm(value.toLowerCase());
  };

  const handleDelete = async (id) => {
    try {
      const response = await fetch(
        `http://localhost/Pure-PHP-CRUD-Project-main/api/delete.php?id=${id}`,
        { method: "DELETE" }
      );

      if (!response.ok) {
        throw new Error("Failed to delete the appointment.");
      }

      const updatedAppointments = $appointments.filter(
        (appointment) => appointment.id !== id
      );
      appointments.set(updatedAppointments);
      alert("Appointment deleted successfully!");
    } catch (error) {
      alert("Error deleting appointment: " + error.message);
    }
  };

  const handleEdit = (appointment) => {
    setEditId(appointment.id); 
    setEditedData({ ...appointment }); 
  };

  const handleSave = async (id) => {
    try {
      const response = await fetch(
        `http://localhost/Pure-PHP-CRUD-Project-main/api/index.php?id=${id}`,
        {
          method: "PUT",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(editedData),
        }
      );

      if (!response.ok) {
        throw new Error("Failed to update the appointment.");
      }

      
      const updatedAppointments = $appointments.map((appointment) =>
        appointment.id === id ? editedData : appointment
      );
      appointments.set(updatedAppointments);

      setEditId(null); 
      alert("Appointment updated successfully!");
    } catch (error) {
      alert("Error updating appointment: " + error.message);
    }
  };

  const handleInputChange = (field, value) => {
    setEditedData((prev) => ({ ...prev, [field]: value }));
  };

  useEffect(() => {
    const styles = `
      .patient-table-container {
        background-color: #ffffff;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
        border: 3px solid #e0e0e0;
        width: 101%;
        margin: 0 auto;
      }
      .patient-table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
      }
      .patient-table-header input {
        padding: 10px;
        width: 300px;
        border: 1px solid #e0e0e0;
        border-radius: 5px;
      }
      .patient-table-header div {
        display: flex;
        gap: 10px;
      }
      .patient-table-header .filters,
      .patient-table-header .download {
        background-color: #0f766e;
        color: #ffffff;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.9rem;
        transition: background-color 0.3s ease;
      }
      .patient-table-header .filters:hover,
      .patient-table-header .download:hover {
        background-color: #134e4a;
      }
      .patient-table {
        width: 100%;
        border-collapse: collapse;
      }
      .patient-table th,
      .patient-table td {
        text-align: left;
        padding: 12px 15px;
        border-bottom: 1px solid #e0e0e0;
      }
      .patient-table th {
        background-color: #f8f9fa;
        font-weight: bold;
        color: #555555;
      }
      .patient-table tbody tr:hover {
        background-color: #f1f5f9;
      }
      .action-buttons button {
        margin-right: 5px;
        background-color: transparent;
        color: #ffffff;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
      }
      .action-buttons button:hover {
        background-color: #134e4a;
      }
        .action-buttons {
  display: flex; 
  gap: 10px; 
}
    `;
    const styleSheet = document.createElement("style");
    styleSheet.type = "text/css";
    styleSheet.innerText = styles;
    document.head.appendChild(styleSheet);

    return () => {
      document.head.removeChild(styleSheet);
    };
  }, []);

  if (loading) {
    return <div>Loading...</div>;
  }

  if (error) {
    return <div>Error: {error}</div>;
  }

  return (
    <div className="patient-table-container">
      <div className="patient-table-header">
        <input
          type="text"
          placeholder="Search here..."
          onChange={(e) => handleSearch(e.target.value)}
        />
        <div>
          <button className="filters">Filters</button>
          <button className="download">Download</button>
        </div>
      </div>

      <table className="patient-table">
        <thead>
          <tr>
            <th>NAME</th>
            <th>MOB</th>
            <th>DATE</th>
            <th>DOCTOR</th>
            <th>DEPARTMENT</th>
            <th>ACTION</th>
          </tr>
        </thead>
        <tbody>
          {$appointments
            .filter(
              (u) =>
                u.name.toLowerCase().includes(searchTerm) ||
                u.mob.toLowerCase().includes(searchTerm) ||
                u.date.toLowerCase().includes(searchTerm) ||
                u.doctor.toLowerCase().includes(searchTerm) ||
                u.department.toLowerCase().includes(searchTerm)
            )
            .map((u) => (
              <tr key={u.id}>
                <td>
                  {editId === u.id ? (
                    <input
                      type="text"
                      value={editedData.name || ""}
                      onChange={(e) => handleInputChange("name", e.target.value)}
                    />
                  ) : (
                    u.name
                  )}
                </td>
                <td>
                  {editId === u.id ? (
                    <input
                      type="text"
                      value={editedData.mob || ""}
                      onChange={(e) => handleInputChange("mob", e.target.value)}
                    />
                  ) : (
                    u.mob
                  )}
                </td>
                <td>
                  {editId === u.id ? (
                    <input
                      type="text"
                      value={editedData.date || ""}
                      onChange={(e) => handleInputChange("date", e.target.value)}
                    />
                  ) : (
                    u.date
                  )}
                </td>
                <td>
                  {editId === u.id ? (
                    <input
                      type="text"
                      value={editedData.doctor || ""}
                      onChange={(e) =>
                        handleInputChange("doctor", e.target.value)
                      }
                    />
                  ) : (
                    u.doctor
                  )}
                </td>
                <td>
                  {editId === u.id ? (
                    <input
                      type="text"
                      value={editedData.department || ""}
                      onChange={(e) =>
                        handleInputChange("department", e.target.value)
                      }
                    />
                  ) : (
                    u.department
                  )}
                </td>
                <td>
                  <div className="action-buttons">
                    {editId === u.id ? (
                      <button onClick={() => handleSave(u.id)}style={{ color: "green" }}>
                        <FontAwesomeIcon icon={faCheck} /></button>
                    ) : (
                      <button onClick={() => handleEdit(u)}style={{ color: "blue" }}>
                        <FontAwesomeIcon icon={faEdit} /></button>
                    )}
                    <button onClick={() => handleDelete(u.id)}style={{ color: "red" }}>
                        <FontAwesomeIcon icon={faTrash} /></button>
                  </div>
                </td>
              </tr>
            ))}
        </tbody>
      </table>
    </div>
  );
};

export default AppointmentsTable;
