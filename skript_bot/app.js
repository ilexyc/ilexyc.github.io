/**
 * Developed by Nikita Lebedev
 *
 * Contact: vk.com/strixoffical
 *
 * @APP.JS Backend for site
 */

var SocketIO  = require('socket.io')(8880);
var MySQL     = require('mysql');

/**
 * MySQL Create pool
 */

var pool = MySQL.createPool({
    connectionLimit: 10,
    database: 'base',
    host: 'localhost',
    user: 'root',
    password: ''
});

/**
 * Export function
 */

var MySQL_Query = function (sql, props) {
    return new Promise(function (resolve, reject) {
        pool.getConnection(function (err, connection) {
            connection.query(
                sql, props,
                function (err, res) {
                    if (err) reject(err);
                    else resolve(res);
                }
            );
            connection.release();
        });
    });
};

/**
 * Functions
 */

var getRandomInt = function (min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

/**
 * Variables
 */

var Variables = {
    ConnectedUsers: 1,
    LastDropID: 0,
    OnlineFactor: 1
};

/**
 * Socket functions
 */

var Update_Online = function () {
    SocketIO.emit('online', (Variables.ConnectedUsers * Variables.OnlineFactor) + getRandomInt(1,10));
};

var Get_Count_Drops = function (callback) {
    var query = "SELECT COUNT(*) AS `count` FROM `drops`";

    MySQL_Query(query).then(function (count) {
        callback(count[0]['count']);
    });
};

var Update_Last_Drop = function () {
    var query = "SELECT * FROM `drops` ORDER BY id DESC LIMIT 1";

    MySQL_Query(query).then(function (drop) {

        var drop = drop[0];

        if (Variables.LastDropID != drop.id) {

            query = "SELECT * FROM `users` WHERE `id` = ?";

            MySQL_Query(query, [drop.user_id]).then(function (user) {

                var user = user[0];

                query = "SELECT * FROM `items` WHERE `id` = ?";

                MySQL_Query(query, [drop.item_id]).then(function (item) {

                    Variables.LastDropID = drop.id;

                    Get_Count_Drops(function (count) {
                        SocketIO.emit('new_drop', {
                            item: item[0],
                            user: {
                                id: user.id,
                                photo: user.photo
                            },
                            cases: count
                        });

                        console.log(count);
                    })

                });
            });
        }
    });
};

/**
 * Socket.on manager
 */

SocketIO.on("connection", function (socket) {
    Variables.ConnectedUsers++;

    Update_Online();

    // Connecting shutdown

    socket.on('disconnect', function () {
        Variables.ConnectedUsers--;

        setTimeout(function () {
            Update_Online();
        }, 1000);
    });

    // Update new drop

    socket.on('new_drop', function () {
        Update_Last_Drop();
    });

});