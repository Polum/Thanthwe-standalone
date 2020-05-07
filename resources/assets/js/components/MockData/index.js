import moment from 'moment'
import uniq from 'lodash/uniq'
import orderBy from 'lodash/orderBy'
// import transactions from './dataSource'
import axios from 'axios';

const tokenProvider = require('axios-token-interceptor');
const instance = axios.create({
    baseURL: 'http://localhost:8000/api'
});
instance.interceptors.request.use(tokenProvider({
    getToken: () => localStorage.getItem('user-token')
}));

const typeOf = o => Object.prototype.toString.call(o).slice(8, -1).toLowerCase()
const purify = o => JSON.parse(JSON.stringify(o)) // purify data

export default function mockData(query) {
    query = purify(query);
    const { limit = 10, offset = 0, sort = 'id', order = '' } = query;
    var rows =

    instance.get('/transactions').then(result =>{
        return result.data.data.data;
    }).catch(err =>{
        console.log(err.response);
    });

  // custom query conditions
//   ['id', 'transaction_type', 'transaction_source', 'account_name', 'transaction_amount', 'transaction_date']
//   .forEach(field => {
//     switch (typeOf(query[field])) {
//       case 'array':
//         rows = rows.filter(row => query[field].includes(row[field]))
//         break
//       case 'string':
//         rows = rows.filter(row => row[field].toLowerCase().includes(query[field].toLowerCase()))
//         break
//       default:
//         // nothing to do
//         break
//     }
//   })

//   if (sort) {
//     rows = orderBy(rows, sort, order)
//   }

//   const res = {
//     rows: rows.slice(offset, offset + limit),
//     total: rows.length
//   }

  const consoleGroupName = 'Mock data - ' + moment().format('YYYY-MM-DD HH:mm:ss')
  setTimeout(() => {
    console.group(consoleGroupName)
    console.info('Receive:', query)
    console.info('Respond:', rows)
    console.groupEnd(consoleGroupName)
  }, 0)
  return Promise.resolve(purify(res))
}
