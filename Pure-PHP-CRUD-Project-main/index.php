---
import Sidebar from '../../components/Sidebar.astro';
import Header from '../../components/Header.astro';
import DashboardStats from '../../components/DashboardStats.astro';
import PatientTable from '../../components/PatientTable.astro';
import DoctorList from '../../components/DoctorList.astro';
---

<style>
  .container {
    display: flex;
    height: 100vh; /* Full viewport height */
    background-color: #f4f6f8; /* Light background */
  }

  .sidebar {
    width: 250px; /* Fixed width for the sidebar */
    background-color: #ffffff; /* Teal background */
  }

  .main-content {
    flex: 1; /* Take the remaining width */
    display: flex;
    flex-direction: column;
  }

  .content-area {
    flex: 1; /* Fill remaining vertical space */
    overflow-y: auto; /* Scrollable if content overflows */
    padding: 20px;
  }

  .grid {
    display: grid;
    grid-template-columns: 2fr 1fr; /* Main content and smaller sidebar */
    gap: 20px;
  }

  @media (max-width: 768px) {
    .grid {
      grid-template-columns: 1fr; /* Single-column layout on smaller screens */
    }
  }
</style>

<div class="container">
  <!-- Sidebar -->
  <div class="sidebar">
    <Sidebar />
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Header -->
    <Header />

    <!-- Content Area -->
    <div class="content-area">
      <!-- Dashboard Stats -->
      <DashboardStats />

      <!-- Main Grid -->
      <div class="grid">
        <!-- Left Content: Patient Table -->
        <PatientTable />

        <!-- Right Content: Doctor List -->
        <DoctorList />
      </div>
    </div>
  </div>
</div>
