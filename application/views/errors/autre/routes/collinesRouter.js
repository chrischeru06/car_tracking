const express = require('express')
const collinesController = require('../controller/collines.controller')

const collinesRouter = express.Router()

collinesRouter.get('/:zoneId', collinesController.getAll)

module.exports = collinesRouter