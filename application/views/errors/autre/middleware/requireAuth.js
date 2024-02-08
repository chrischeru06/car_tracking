const requireAuth = (request, response, next) => {
          if(request.clientId) {
                    next()
          } else {
                    response.status(401).json({
                              errors: {
                                        main: "You need to be connected"
                              }
                    })
          }
}

module.exports = requireAuth