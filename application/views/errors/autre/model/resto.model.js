const { query } = require("../functions/db")
/**
 * recuperer tous les menus en fonction des filtres
 * @param {Number} categoryId la longitude du client
 * @param {Number} restoId la latitude du client
 * @param {String} q la recherche
 * @param {Number} offset l'offset
 * @param {Number} limit la limit
 * @returns Promise
 */
const getAll = async (latitude, longitude, q, offset = 0, limit = 10) => {
          try {
                    var binds = []
                    var sqlQuery = "SELECT resto.*, resto.TEL_RESPONSABLE, resto.ADRESSE, resto.MAP_URL "
                    if(latitude && longitude) {
                              sqlQuery += `,( 6371 * acos( cos( radians(${latitude}) ) * cos( radians( resto.LATITUDE ) ) * cos( radians(resto.LONGITUDE) - radians(${longitude})) + sin(radians(${latitude})) * sin( radians(resto.LATITUDE)))) AS DISTANCE `
                    }
                    sqlQuery += "FROM restaurants resto WHERE 1 "
                    if(q && q != '') {
                              sqlQuery += "AND (resto.NOM_RESTAURANT LIKE ?) "
                              binds.push(`%${q}%`)
                    }
                    if(latitude && longitude) {
                              // sqlQuery += " HAVING DISTANCE < 10 "
                              sqlQuery += " ORDER BY DISTANCE ASC "
                    }
                    sqlQuery += `LIMIT ${offset}, ${limit}`
                    return await query(sqlQuery, binds)
          } catch (error) {
                    console.log(error)
                    throw error
          }
}

const getResto = async (restoId) => {
          try {
                    return query('SELECT * FROM restaurants WHERE ID_RESTAURANT  = ? ', [restoId])
          } catch (error) {
                    console.log(error)
                    throw error
          }
}

module.exports ={
          getAll,
          getResto
}