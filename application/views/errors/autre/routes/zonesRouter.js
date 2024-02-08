const express = require('express')
const zonesController = require('../controller/zones.controller')

const zonesRouter = express.Router()

zonesRouter.get('/:communeId', zonesController.getAll)

module.exports = zonesRouter