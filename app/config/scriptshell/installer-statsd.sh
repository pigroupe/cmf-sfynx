#!/bin/bash

# StatsD
echo "#### installing statsd"
pushd /opt
sudo git clone https://github.com/etsy/statsd.git
cat >> /tmp/localConfig.js << EOF
{
 port: 8125
, dumpMessages: true
, debug: true
, mongoHost: 'localhost'
, mongoPort: 27017
, mongoMax: 2160
, mongoPrefix: true
, mongoName: 'statsD'
, backends: ['/opt/statsd/mongo-statsd-backend/lib/index.js']
}
EOF
 
sudo cp /tmp/localConfig.js /opt/statsd/localConfig.js
popd
