const restoModel = require('../model/resto.model')

const getAll = async (req, res) => {
          try {
                    const { lat, long, q, offset, limit } = req.query
                    const restaurants = await restoModel.getAll(lat, long, q, offset, limit)
                    res.status(200).json(restaurants)
          } catch (error) {
                    console.log(error)
                    res.status(500).send('Server error')
          }
}

module.exports = {
          getAll
}