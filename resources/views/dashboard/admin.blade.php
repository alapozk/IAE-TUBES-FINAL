{{-- resources/views/dashboard/admin.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  .dashboard-wrapper { min-height: 100vh; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 20px; }
  .dashboard-container { max-width: 1400px; margin: 0 auto; }
  .dashboard-header { margin-bottom: 40px; animation: slideDown 0.6s ease-out; }
  .dashboard-header h1 { font-size: 2.5rem; font-weight: 900; color: white; margin-bottom: 10px; }
  .dashboard-header p { font-size: 1.1rem; color: rgba(255, 255, 255, 0.9); }
  .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin-bottom: 30px; }
  .stat-card { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12); transition: all 0.3s ease; }
  .stat-card:hover { transform: translateY(-5px); }
  .stat-value { font-size: 2rem; font-weight: 800; color: #667eea; margin-bottom: 5px; }
  .stat-label { font-size: 0.9rem; color: #718096; font-weight: 600; }
  .content-card { background: white; border-radius: 15px; padding: 25px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12); margin-bottom: 25px; }
  .tab-nav { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
  .tab-btn { padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all .2s; background: rgba(255,255,255,0.2); color: white; }
  .tab-btn.active { background: white; color: #667eea; }
  .tab-btn:hover:not(.active) { background: rgba(255,255,255,0.3); }
  table { width: 100%; border-collapse: collapse; }
  th, td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #e2e8f0; }
  th { font-weight: 600; color: #718096; font-size: .85rem; text-transform: uppercase; }
  td { color: #1a202c; }
  tr:hover { background: #f7fafc; }
  .badge { padding: 4px 10px; border-radius: 20px; font-size: .75rem; font-weight: 600; }
  .badge-active { background: #d1fae5; color: #059669; }
  .badge-inactive { background: #fed7d7; color: #c53030; }
  .badge-teacher { background: #dbeafe; color: #2563eb; }
  .badge-student { background: #fef3c7; color: #d97706; }
  .badge-admin { background: #ede9fe; color: #7c3aed; }
  .search-row { display: flex; gap: 10px; margin-bottom: 15px; align-items: center; flex-wrap: wrap; }
  .search-input { flex: 1; min-width: 200px; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem; }
  .search-input:focus { outline: none; border-color: #667eea; }
  .btn { padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all .2s; }
  .btn-primary { background: #667eea; color: white; }
  .btn-primary:hover { background: #5a67d8; }
  .btn-success { background: #10b981; color: white; }
  .btn-success:hover { background: #059669; }
  .btn-danger { background: #ef4444; color: white; }
  .btn-danger:hover { background: #dc2626; }
  .btn-sm { padding: 6px 12px; font-size: .85rem; }
  .action-btns { display: flex; gap: 8px; }
  .loading { text-align: center; padding: 40px; color: #718096; }
  .error { background: #fee2e2; color: #991b1b; padding: 16px; border-radius: 12px; margin-bottom: 20px; }
  .success { background: #d1fae5; color: #065f46; padding: 16px; border-radius: 12px; margin-bottom: 20px; }
  /* Modal */
  .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1000; padding: 20px; }
  .modal { background: white; border-radius: 16px; padding: 30px; max-width: 500px; width: 100%; max-height: 90vh; overflow-y: auto; }
  .modal h2 { font-size: 1.5rem; font-weight: 800; margin-bottom: 20px; color: #1a202c; }
  .form-group { margin-bottom: 15px; }
  .form-group label { display: block; font-weight: 600; margin-bottom: 5px; color: #4a5568; }
  .form-group input, .form-group select { width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem; }
  .form-group input:focus, .form-group select:focus { outline: none; border-color: #667eea; }
  .modal-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; }
  @keyframes slideDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="dashboard-wrapper" x-data="adminDashboard()" x-init="loadData()">
  <div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
      <h1>🛠️ Admin Dashboard</h1>
      <p>Kelola kursus dan pengguna platform EduConnect</p>
    </div>

    <!-- Error/Success -->
    <div class="error" x-show="error" x-text="error"></div>
    <div class="success" x-show="success" x-text="success"></div>

    <!-- Stats -->
    <div class="stats-grid" x-show="stats">
      <div class="stat-card">
        <div class="stat-value" x-text="stats?.totalCourses || 0"></div>
        <div class="stat-label">📚 Total Kursus</div>
      </div>
      <div class="stat-card">
        <div class="stat-value" x-text="stats?.activeCourses || 0"></div>
        <div class="stat-label">✅ Kursus Aktif</div>
      </div>
      <div class="stat-card">
        <div class="stat-value" x-text="stats?.totalTeachers || 0"></div>
        <div class="stat-label">👨‍🏫 Total Guru</div>
      </div>
      <div class="stat-card">
        <div class="stat-value" x-text="stats?.totalStudents || 0"></div>
        <div class="stat-label">👨‍🎓 Total Siswa</div>
      </div>
      <div class="stat-card">
        <div class="stat-value" x-text="stats?.totalEnrollments || 0"></div>
        <div class="stat-label">📝 Pendaftaran</div>
      </div>
    </div>

    <!-- Tab Navigation -->
    <div class="tab-nav">
      <button class="tab-btn" :class="{ 'active': activeTab === 'courses' }" @click="activeTab = 'courses'">📚 Kursus</button>
      <button class="tab-btn" :class="{ 'active': activeTab === 'teachers' }" @click="activeTab = 'teachers'; loadUsers('teacher')">👨‍🏫 Guru</button>
      <button class="tab-btn" :class="{ 'active': activeTab === 'students' }" @click="activeTab = 'students'; loadUsers('student')">👨‍🎓 Siswa</button>
    </div>

    <!-- Loading -->
    <div class="content-card" x-show="loading">
      <div class="loading">Memuat data...</div>
    </div>

    <!-- Courses Tab -->
    <div class="content-card" x-show="activeTab === 'courses' && !loading">
      <input type="text" class="search-input" placeholder="🔍 Cari kursus..." x-model="searchCourse" @input="filterCourses()">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Judul Kursus</th>
            <th>Kode</th>
            <th>Guru</th>
            <th>Siswa</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <template x-for="course in filteredCourses" :key="course.id">
            <tr>
              <td x-text="course.id"></td>
              <td x-text="course.title"></td>
              <td x-text="course.code || '-'"></td>
              <td x-text="course.teacher?.name || '-'"></td>
              <td x-text="course.student_count || 0"></td>
              <td><span class="badge" :class="course.status === 'active' ? 'badge-active' : 'badge-inactive'" x-text="course.status || 'draft'"></span></td>
            </tr>
          </template>
        </tbody>
      </table>
      <p x-show="filteredCourses.length === 0" class="loading">Tidak ada kursus ditemukan</p>
    </div>

    <!-- Teachers/Students Tab -->
    <div class="content-card" x-show="(activeTab === 'teachers' || activeTab === 'students') && !loading">
      <div class="search-row">
        <input type="text" class="search-input" placeholder="🔍 Cari pengguna..." x-model="searchUser" @input="filterUsers()">
        <button class="btn btn-primary" @click="openCreateModal()">➕ Tambah User</button>
      </div>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Terdaftar</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <template x-for="user in filteredUsers" :key="user.id">
            <tr>
              <td x-text="user.id"></td>
              <td x-text="user.name"></td>
              <td x-text="user.email"></td>
              <td><span class="badge" :class="getRoleBadge(user.role)" x-text="user.role"></span></td>
              <td x-text="formatDate(user.created_at)"></td>
              <td>
                <div class="action-btns">
                  <button class="btn btn-sm btn-success" @click="openEditModal(user)">✏️ Edit</button>
                  <button class="btn btn-sm btn-danger" @click="confirmDelete(user)">🗑️ Hapus</button>
                </div>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
      <p x-show="filteredUsers.length === 0" class="loading">Tidak ada pengguna ditemukan</p>
    </div>

    <!-- Create/Edit User Modal -->
    <div class="modal-overlay" x-show="showModal" @click.self="showModal = false" x-cloak>
      <div class="modal">
        <h2 x-text="editingUser ? '✏️ Edit User' : '➕ Tambah User Baru'"></h2>
        
        <div class="form-group">
          <label>Nama</label>
          <input type="text" x-model="form.name" placeholder="Nama lengkap">
        </div>
        
        <div class="form-group">
          <label>Email</label>
          <input type="email" x-model="form.email" placeholder="email@example.com">
        </div>
        
        <div class="form-group">
          <label>Role</label>
          <select x-model="form.role">
            <option value="">Pilih Role</option>
            <option value="teacher">Guru</option>
            <option value="student">Siswa</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        
        <div class="form-group">
          <label x-text="editingUser ? 'Password (kosongkan jika tidak diubah)' : 'Password'"></label>
          <input type="password" x-model="form.password" placeholder="********">
        </div>
        
        <div class="modal-actions">
          <button class="btn" @click="showModal = false">Batal</button>
          <button class="btn btn-primary" @click="saveUser()" :disabled="saving" x-text="saving ? 'Menyimpan...' : 'Simpan'"></button>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" x-show="showDeleteModal" @click.self="showDeleteModal = false" x-cloak>
      <div class="modal">
        <h2>⚠️ Konfirmasi Hapus</h2>
        <p style="margin-bottom: 20px; color: #4a5568;">
          Yakin ingin menghapus user <strong x-text="deleteTarget?.name"></strong>? Tindakan ini tidak dapat dibatalkan.
        </p>
        <div class="modal-actions">
          <button class="btn" @click="showDeleteModal = false">Batal</button>
          <button class="btn btn-danger" @click="deleteUser()" :disabled="saving" x-text="saving ? 'Menghapus...' : 'Hapus'"></button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function adminDashboard() {
  return {
    activeTab: 'courses',
    loading: true,
    error: null,
    success: null,
    stats: null,
    courses: [],
    users: [],
    filteredCourses: [],
    filteredUsers: [],
    searchCourse: '',
    searchUser: '',
    showModal: false,
    showDeleteModal: false,
    editingUser: null,
    deleteTarget: null,
    saving: false,
    form: { name: '', email: '', role: '', password: '' },

    async loadData() {
      try {
        const query = `query {
          adminStats { totalCourses totalTeachers totalStudents totalEnrollments activeCourses }
          adminCourses { id title code status student_count teacher { id name email } }
        }`;
        const result = await GraphQL.query(query);
        this.stats = result.adminStats;
        this.courses = result.adminCourses || [];
        this.filteredCourses = this.courses;
      } catch (e) {
        this.error = e.message;
      } finally {
        this.loading = false;
      }
    },

    async loadUsers(role) {
      this.loading = true;
      this.error = null;
      try {
        const query = `query AdminUsers($role: String) { adminUsers(role: $role) { id name email role created_at } }`;
        const result = await GraphQL.query(query, { role });
        this.users = result.adminUsers || [];
        this.filteredUsers = this.users;
      } catch (e) {
        this.error = e.message;
      } finally {
        this.loading = false;
      }
    },

    openCreateModal() {
      this.editingUser = null;
      this.form = { name: '', email: '', role: this.activeTab === 'teachers' ? 'teacher' : 'student', password: '' };
      this.showModal = true;
    },

    openEditModal(user) {
      this.editingUser = user;
      this.form = { name: user.name, email: user.email, role: user.role, password: '' };
      this.showModal = true;
    },

    confirmDelete(user) {
      this.deleteTarget = user;
      this.showDeleteModal = true;
    },

    async saveUser() {
      if (!this.form.name || !this.form.email || !this.form.role) {
        this.error = 'Semua field wajib diisi.';
        return;
      }
      if (!this.editingUser && !this.form.password) {
        this.error = 'Password wajib diisi untuk user baru.';
        return;
      }

      this.saving = true;
      this.error = null;

      try {
        if (this.editingUser) {
          const mutation = `mutation AdminUpdateUser($id: ID!, $name: String!, $email: String!, $role: String!, $password: String) {
            adminUpdateUser(id: $id, name: $name, email: $email, role: $role, password: $password) { id name email role }
          }`;
          await GraphQL.mutate(mutation, { id: this.editingUser.id, ...this.form });
          this.success = 'User berhasil diupdate!';
        } else {
          const mutation = `mutation AdminCreateUser($name: String!, $email: String!, $password: String!, $role: String!) {
            adminCreateUser(name: $name, email: $email, password: $password, role: $role) { id name email role }
          }`;
          await GraphQL.mutate(mutation, this.form);
          this.success = 'User berhasil ditambahkan!';
        }
        this.showModal = false;
        await this.loadUsers(this.activeTab === 'teachers' ? 'teacher' : 'student');
        await this.loadData(); // Refresh stats
        setTimeout(() => this.success = null, 3000);
      } catch (e) {
        this.error = e.message;
      } finally {
        this.saving = false;
      }
    },

    async deleteUser() {
      this.saving = true;
      this.error = null;

      try {
        const mutation = `mutation AdminDeleteUser($id: ID!) { adminDeleteUser(id: $id) }`;
        await GraphQL.mutate(mutation, { id: this.deleteTarget.id });
        this.success = 'User berhasil dihapus!';
        this.showDeleteModal = false;
        await this.loadUsers(this.activeTab === 'teachers' ? 'teacher' : 'student');
        await this.loadData();
        setTimeout(() => this.success = null, 3000);
      } catch (e) {
        this.error = e.message;
      } finally {
        this.saving = false;
      }
    },

    getRoleBadge(role) {
      if (role === 'teacher') return 'badge-teacher';
      if (role === 'student') return 'badge-student';
      if (role === 'admin') return 'badge-admin';
      return '';
    },

    filterCourses() {
      const search = this.searchCourse.toLowerCase();
      this.filteredCourses = this.courses.filter(c => c.title.toLowerCase().includes(search) || (c.code && c.code.toLowerCase().includes(search)) || (c.teacher?.name && c.teacher.name.toLowerCase().includes(search)));
    },

    filterUsers() {
      const search = this.searchUser.toLowerCase();
      this.filteredUsers = this.users.filter(u => u.name.toLowerCase().includes(search) || u.email.toLowerCase().includes(search));
    },

    formatDate(dateStr) {
      if (!dateStr) return '-';
      const date = new Date(dateStr);
      return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    }
  }
}
</script>
@endsection