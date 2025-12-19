@extends('layouts.app')

@section('content')
<style>
    .admin-wrapper{min-height:100vh;background:#f7fafc;padding:40px 20px}
    .admin-container{max-width:1200px;margin:0 auto}
    .admin-header{margin-bottom:30px}
    .admin-header h1{font-size:2rem;font-weight:900;color:#1a202c;margin-bottom:8px}
    .admin-header p{color:#718096}
    .stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin-bottom:30px}
    .stat-card{background:#fff;border-radius:16px;padding:24px;box-shadow:0 4px 15px rgba(0,0,0,.08)}
    .stat-value{font-size:2.5rem;font-weight:900;color:#4f46e5}
    .stat-label{font-size:.875rem;color:#718096;margin-top:5px}
    .tab-nav{display:flex;gap:10px;margin-bottom:20px}
    .tab-btn{padding:12px 24px;border:none;border-radius:10px;font-weight:600;cursor:pointer;transition:all .2s}
    .tab-btn.active{background:#4f46e5;color:#fff}
    .tab-btn:not(.active){background:#fff;color:#4f46e5;border:2px solid #e2e8f0}
    .tab-btn:hover:not(.active){border-color:#4f46e5}
    .table-card{background:#fff;border-radius:16px;padding:24px;box-shadow:0 4px 15px rgba(0,0,0,.08)}
    .search-bar{display:flex;gap:10px;margin-bottom:20px}
    .search-input{flex:1;padding:12px 16px;border:2px solid #e2e8f0;border-radius:10px;font-size:1rem}
    .search-input:focus{outline:none;border-color:#4f46e5}
    table{width:100%;border-collapse:collapse}
    th,td{padding:12px 16px;text-align:left;border-bottom:1px solid #e2e8f0}
    th{font-weight:600;color:#718096;font-size:.85rem;text-transform:uppercase}
    td{color:#1a202c}
    tr:hover{background:#f7fafc}
    .badge{padding:4px 10px;border-radius:20px;font-size:.75rem;font-weight:600}
    .badge-active{background:#d1fae5;color:#059669}
    .badge-inactive{background:#fed7d7;color:#c53030}
    .badge-teacher{background:#dbeafe;color:#2563eb}
    .badge-student{background:#fef3c7;color:#d97706}
    .loading{text-align:center;padding:50px;color:#718096}
    .error{background:#fee2e2;color:#991b1b;padding:16px;border-radius:12px;margin-bottom:20px}
</style>

<div class="admin-wrapper" x-data="adminPage()" x-init="loadData()">
    <div class="admin-container">
        <div class="admin-header">
            <h1>🛠️ Admin Dashboard</h1>
            <p>Kelola kursus dan pengguna sistem</p>
        </div>

        <!-- Error -->
        <div class="error" x-show="error" x-text="error"></div>

        <!-- Stats -->
        <div class="stats-grid" x-show="stats">
            <div class="stat-card">
                <div class="stat-value" x-text="stats?.totalCourses || 0"></div>
                <div class="stat-label">Total Kursus</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" x-text="stats?.activeCourses || 0"></div>
                <div class="stat-label">Kursus Aktif</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" x-text="stats?.totalTeachers || 0"></div>
                <div class="stat-label">Total Guru</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" x-text="stats?.totalStudents || 0"></div>
                <div class="stat-label">Total Siswa</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" x-text="stats?.totalEnrollments || 0"></div>
                <div class="stat-label">Total Pendaftaran</div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="tab-nav">
            <button class="tab-btn" :class="{ 'active': activeTab === 'courses' }" @click="activeTab = 'courses'">
                📚 Kursus
            </button>
            <button class="tab-btn" :class="{ 'active': activeTab === 'teachers' }" @click="activeTab = 'teachers'; loadUsers('teacher')">
                👨‍🏫 Guru
            </button>
            <button class="tab-btn" :class="{ 'active': activeTab === 'students' }" @click="activeTab = 'students'; loadUsers('student')">
                👨‍🎓 Siswa
            </button>
        </div>

        <!-- Loading -->
        <div class="loading" x-show="loading">Memuat data...</div>

        <!-- Courses Tab -->
        <div class="table-card" x-show="activeTab === 'courses' && !loading">
            <div class="search-bar">
                <input type="text" class="search-input" placeholder="Cari kursus..." x-model="searchCourse" @input="filterCourses()">
            </div>
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
                            <td>
                                <span class="badge" :class="course.status === 'active' ? 'badge-active' : 'badge-inactive'" x-text="course.status || 'draft'"></span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <p x-show="filteredCourses.length === 0" class="loading">Tidak ada kursus ditemukan</p>
        </div>

        <!-- Teachers/Students Tab -->
        <div class="table-card" x-show="(activeTab === 'teachers' || activeTab === 'students') && !loading">
            <div class="search-bar">
                <input type="text" class="search-input" placeholder="Cari pengguna..." x-model="searchUser" @input="filterUsers()">
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Terdaftar</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="user in filteredUsers" :key="user.id">
                        <tr>
                            <td x-text="user.id"></td>
                            <td x-text="user.name"></td>
                            <td x-text="user.email"></td>
                            <td>
                                <span class="badge" :class="user.role === 'teacher' ? 'badge-teacher' : 'badge-student'" x-text="user.role"></span>
                            </td>
                            <td x-text="formatDate(user.created_at)"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <p x-show="filteredUsers.length === 0" class="loading">Tidak ada pengguna ditemukan</p>
        </div>
    </div>
</div>

<script>
function adminPage() {
    return {
        activeTab: 'courses',
        loading: true,
        error: null,
        stats: null,
        courses: [],
        users: [],
        filteredCourses: [],
        filteredUsers: [],
        searchCourse: '',
        searchUser: '',

        async loadData() {
            try {
                // Load stats and courses
                const query = `
                    query {
                        adminStats {
                            totalCourses
                            totalTeachers
                            totalStudents
                            totalEnrollments
                            activeCourses
                        }
                        adminCourses {
                            id
                            title
                            code
                            status
                            student_count
                            teacher { id name email }
                        }
                    }
                `;
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
            try {
                const query = `
                    query AdminUsers($role: String) {
                        adminUsers(role: $role) {
                            id
                            name
                            email
                            role
                            created_at
                        }
                    }
                `;
                const result = await GraphQL.query(query, { role });
                this.users = result.adminUsers || [];
                this.filteredUsers = this.users;
            } catch (e) {
                this.error = e.message;
            } finally {
                this.loading = false;
            }
        },

        filterCourses() {
            const search = this.searchCourse.toLowerCase();
            this.filteredCourses = this.courses.filter(c => 
                c.title.toLowerCase().includes(search) ||
                (c.code && c.code.toLowerCase().includes(search)) ||
                (c.teacher?.name && c.teacher.name.toLowerCase().includes(search))
            );
        },

        filterUsers() {
            const search = this.searchUser.toLowerCase();
            this.filteredUsers = this.users.filter(u => 
                u.name.toLowerCase().includes(search) ||
                u.email.toLowerCase().includes(search)
            );
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
