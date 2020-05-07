import Dashboard from './components/Dashboard';
import Notifications from './components/Notifications';
import Users from './components/Users';
import DepartmentsMinistries from './components/DepartmentsMinistries';
import Profile from './components/Profile';
import Members from './components/Members';
import MemberProfile from './components/MemberProfile';
import Accounts from './components/Accounts';
import Transactions from './components/Transactions';
import UpcomingActivities from './components/UpcomingActivities';
import ActivitiesCalendar from './components/ActivitiesCalendar';
import Zones from './components/Zones';
import ManageHomecells from './components/ManageHomecells';
import Attendance from './components/Attendance';
import Tithe from './components/Tithe';
import SundayBasket from './components/SundayBasket';
import OtherOfferings from './components/OtherOfferings';
import Reports from './components/Reports';
import Login from './components/Login';


export default[
    { path:'/', meta:{ layout: 'no-sidebar' }, component: Login },
    { path:'/dashboard', meta:{ requireAuth: true }, component: Dashboard },
    { path:'/notifications', meta:{ requireAuth: true }, component: Notifications },
    { path:'/users', meta:{ requireAuth: true }, component: Users },
    { path:'/departments_and_ministries', meta:{ requireAuth: true }, component: DepartmentsMinistries },
    { path:'/profile', meta:{ requireAuth: true }, component: Profile },
    { path:'/members', meta:{ requireAuth: true }, component: Members },
    { path:'/member-profile', meta:{ requireAuth: true }, name:"member profile", component: MemberProfile },
    { path:'/accounts', meta:{ requireAuth: true }, component: Accounts },
    { path: '/transactions', meta:{ requireAuth: true }, component: Transactions },
    { path: '/upcoming-activities', meta:{ requireAuth: true }, component: UpcomingActivities },
    { path: '/calendar', meta:{ requireAuth: true }, component: ActivitiesCalendar },
    { path: '/zones', meta:{ requireAuth: true }, component: Zones },
    { path: '/homecells', meta:{ requireAuth: true }, component: ManageHomecells },
    { path: '/attendance', meta:{ requireAuth: true }, name:"attendance", component: Attendance },
    { path: '/tithes', meta:{ requireAuth: true }, component: Tithe },
    { path: '/sunday-basket', meta:{ requireAuth: true }, component: SundayBasket},
    { path: '/other-offerings', meta:{ requireAuth: true }, component: OtherOfferings},
    { path: '/reports', meta:{ requireAuth: true }, component: Reports},
    { path: '/login', meta:{ layout: 'no-sidebar'}, name:"login", component: Login}
]
