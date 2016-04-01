Awesome Microservices |Awesome|
===============================

A curated list of Microservice Architecture related principles and
technologies.

.. raw:: html

   <!-- START doctoc generated TOC please keep comment here to allow auto update -->
   <!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->

**Table of Contents**

-  `Platforms <#platforms>`__
-  `Runtimes <#runtimes>`__
-  `Service Toolkits <#service-toolkits>`__
-  `Agnostic <#agnostic>`__
-  `C <#c>`__
-  `C++ <#c-1>`__
-  `D <#d>`__
-  `Erlang VM <#erlang-vm>`__
-  `Go <#go>`__
-  `Haskell <#haskell>`__
-  `Java VM <#java-vm>`__
-  `Node.js <#nodejs>`__
-  `Perl <#perl>`__
-  `Python <#python>`__
-  `Ruby <#ruby>`__
-  `Capabilities <#capabilities>`__
-  `API Gateways / Edge Services <#api-gateways--edge-services>`__
-  `Configuration and Discovery <#configuration-and-discovery>`__
-  `Security <#security>`__
-  `Elasticity <#elasticity>`__
-  `Messaging <#messaging>`__
-  `Serialization <#serialization>`__
-  `Storage <#storage>`__
-  `Reactivity <#reactivity>`__
-  `Resilience <#resilience>`__
-  `Testing <#testing>`__
-  `Monitoring and Debugging <#monitoring-and-debugging>`__
-  `Logging <#logging>`__
-  `IT Automation / Provisioning <#it-automation--provisioning>`__
-  `Deployment and Continuous
   Integration <#deployment-and-continuous-integration>`__
-  `On-prem <#on-prem>`__
-  `Hosted <#hosted>`__
-  `Lightweight <#lightweight>`__
-  `Containers <#containers>`__
-  `Documentation & Modeling <#documentation--modeling>`__
-  `REST APIs <#rest-apis>`__
-  `Standards / Recommendations <#standards--recommendations>`__
-  `World Wide Web <#world-wide-web>`__
-  `HTTP/1.1 <#http11>`__
-  `HTTP/2 <#http2>`__
-  `RPC <#rpc>`__
-  `Messaging <#messaging-1>`__
-  `Security <#security-1>`__
-  `Service Discovery <#service-discovery>`__
-  `Data Formats <#data-formats>`__
-  `Unicode <#unicode>`__
-  `Real Life Stories <#real-life-stories>`__
-  `Theory <#theory>`__
-  `Articles & Papers <#articles--papers>`__
-  `Tutorials <#tutorials>`__
-  `Books <#books>`__
-  `Sites <#sites>`__
-  `Emerging Technologies <#emerging-technologies>`__
-  `License <#license>`__
-  `Contributing <#contributing>`__
-  `Acknowledgments <#acknowledgments>`__

.. raw:: html

   <!-- END doctoc generated TOC please keep comment here to allow auto update -->

Platforms
~~~~~~~~~

-  `Cisco
   Microservices <https://github.com/CiscoCloud/microservices-infrastructure>`__
   - Modern platform for rapidly deploying globally distributed
   services.
-  `Cocaine <https://github.com/cocaine>`__ - A cloud platform enabling
   you to build your own PaaS clouds.
-  `Deis <http://deis.io/>`__ - Open-source application platform for
   public and private clouds.
-  `Fabric8 <http://fabric8.io/>`__ - Open-source integration platform
   for deep management of Java Containers (JVMs).
-  `H2 <https://github.com/hailocab/h2>`__ - Hailo's microservices
   platform.
-  `Hook.io <https://hook.io/>`__ - Open-source hosting platform for
   microservices.
-  `Lattice <http://lattice.cf/>`__ - Open-source project for running
   containerized workloads on a cluster. Lattice bundles up http
   load-balancing, a cluster scheduler, log aggregation/streaming and
   health management into an easy-to-deploy and easy-to-use package.
-  `Netflix OSS <https://netflix.github.io/>`__ - Netflix open-source
   software ecosystem.
-  `Spring Cloud
   Netflix <https://github.com/spring-cloud/spring-cloud-netflix>`__ -
   Provides Netflix OSS integrations for Spring Boot apps through
   autoconfiguration and binding to the Spring Environment and other
   Spring programming model idioms.
-  `VAMP <http://vamp.io/>`__ - Build, deploy and manage microservices
   with power and ease.

Runtimes
--------

-  `Akka <http://akka.io/>`__ - Toolkit and runtime for building highly
   concurrent, distributed, and resilient message-driven applications on
   the JVM.
-  `Baratine <http://baratine.io/>`__ - Platform for building a network
   of loosely-coupled POJO microservices.
-  `Erlang/OTP <https://github.com/erlang/otp>`__ - Programming language
   used to build massively scalable soft real-time systems with
   requirements on high availability.
-  `Finagle <http://twitter.github.io/finagle>`__ - Extensible RPC
   system for the JVM, used to construct high-concurrency servers.
-  `Karyon <https://github.com/Netflix/karyon>`__ - The nucleus or the
   base container for applications and services built using the
   NetflixOSS ecosystem.
-  `Microserver <https://github.com/aol/micro-server>`__ - Java 8
   native, zero configuration, standards based, battle hardened library
   to run Java REST microservices.
-  `Orbit <http://orbit.bioware.com/>`__ - Modern framework for JVM
   languages that makes it easier to build and maintain distributed and
   scalable online services.
-  `Service Fabric I/O <http://scalecube.io>`__ - A microservices
   framework for the rapid development of distributed, resilient,
   reactive applications at scale.
-  `Vert.X <http://vertx.io/>`__ - Toolkit for building reactive
   applications on the JVM.

Service Toolkits
----------------

Agnostic
~~~~~~~~

-  `Apex <https://github.com/apex/apex>`__ - Tool for deploying and
   managing AWS Lambda functions. With shims for languages not yet
   supported by Lambda, you can use Golang out of the box.
-  `GRPC <http://www.grpc.io/>`__ - A high performance, open source,
   general RPC framework that puts mobile and HTTP/2 first. Libraries in
   C, C++, Java, Go, Node.js, Python, Ruby, Objective-C, PHP and C#.

C
~

-  `Kore <https://kore.io/>`__ - Easy to use web application framework
   for writing scalable web APIs in C.
-  `Libasyncd <https://github.com/wolkykim/libasyncd/>`__ - Embeddable
   event-based asynchronous HTTP server library for C.
-  `Libslack <http://libslack.org/>`__ - Provides a generic agent
   oriented programming model, run time selection of locking strategies,
   functions that make writing daemons trivial and simplify the
   implementation of network servers and clients, &c.
-  `Lwan <http://lwan.ws/>`__ - High-performance and scalable web
   server.
-  `Onion <https://github.com/davidmoreno/onion>`__ - C library to
   create simple HTTP servers and web applications.
-  `RIBS2 <https://github.com/Adaptv/ribs2>`__ - Library which allows
   building high-performance internet serving systems.

C++
~~~

.. raw:: html

   <!-- #c-1 anchor -->

-  `AnyRPC <https://github.com/sgieseking/anyrpc>`__ - Provides a common
   system to work with a number of different remote procedure call
   standards, including: JSON-RPC, XML-RPC, MessagePack-RPC.
-  `Cap’n Proto RPC <https://capnproto.org/cxxrpc.html>`__ - The Cap’n
   Proto C++ RPC implementation.
-  `C++ Micro Services <http://cppmicroservices.org/>`__ - An OSGi-like
   C++ dynamic module system and service registry.
-  `Enduro/X <https://github.com/endurox-dev/endurox/>`__ - XATMI based
   service framework for GNU/Linux.
-  `Pion <https://github.com/splunk/pion>`__ - C++ framework for
   building lightweight HTTP interfaces.
-  `Served <https://github.com/datasift/served>`__ - C++ library for
   building high performance RESTful web servers.
-  `ULib <https://github.com/stefanocasazza/ULib>`__ - Highly optimized
   class framework for writing C++ applications.

D
~

-  `Vibe.d <http://vibed.org/>`__ - Asynchronous I/O that doesn’t get in
   your way, written in D.

Erlang VM
~~~~~~~~~

Elixir
^^^^^^

-  `Phoenix <http://www.phoenixframework.org/>`__ - Framework for
   building HTML5 apps, API backends and distributed systems.
-  `Plug <https://github.com/elixir-lang/plug>`__ - A specification and
   conveniences for composable modules between web applications.

Erlang
^^^^^^

-  `Cowboy <https://github.com/ninenines/cowboy>`__ - Small, fast,
   modular HTTP server written in Erlang.
-  `Gen
   Microservice <https://github.com/videlalvaro/gen_microservice>`__ -
   This library solves the problem of implementing microservices with
   Erlang.
-  `Mochiweb <https://github.com/mochi/mochiweb>`__ - Erlang library for
   building lightweight HTTP servers.

Go
~~

-  `Gin <http://gin-gonic.github.io/gin/>`__ - Web framework written in
   Golang.
-  `Goa <https://github.com/goadesign/goa>`__ - Design-based HTTP
   microservices in Go.
-  `Gocraft <https://github.com/gocraft/web>`__ - A toolkit for building
   web apps. Includes routing, middleware stacks, logging and
   monitoring.
-  `Goji <https://goji.io/>`__ - Minimalistic and flexible request
   multiplexer for Go.
-  `Go kit <https://github.com/go-kit/kit>`__ - Distributed programming
   toolkit for microservices in the modern enterprise.
-  `Gorilla <http://www.gorillatoolkit.org/>`__ - Web toolkit for the Go
   programming language.
-  `Kite <https://github.com/koding/kite>`__ - Microservices framework
   in Go.
-  `Libchan <https://github.com/docker/libchan>`__ - Ultra-lightweight
   networking library which lets network services communicate in the
   same way that goroutines communicate using channels.
-  `Macaron <https://go-macaron.com/>`__ - Modular web framework in Go.
-  `Micro <https://github.com/micro/micro>`__ - A microservices
   toolchain in Go.
-  `Negroni <https://github.com/codegangsta/negroni>`__ - Idiomatic HTTP
   middleware for Golang.

Haskell
~~~~~~~

-  `Scotty <https://github.com/scotty-web/scotty>`__ - Micro web
   framework inspired by Ruby's Sinatra, using WAI and Warp.
-  `Yesod <https://github.com/yesodweb/yesod>`__ - The Haskell RESTful
   web framework.

Java VM
~~~~~~~

Clojure
^^^^^^^

-  `Compojure <https://github.com/weavejester/compojure>`__ - A concise
   routing library for Ring/Clojure.
-  `Duct <https://github.com/weavejester/duct>`__ - Minimal framework
   for building web applications in Clojure, with a strong emphasis on
   simplicity.
-  `Liberator <http://clojure-liberator.github.io/liberator/>`__ -
   Library that helps you expose your data as resources while
   automatically complying with all the relevant requirements of the
   HTTP specification.
-  `Modularity <https://modularity.org/>`__ - JUXT's Clojure-based
   modular system.
-  `System <https://github.com/danielsz/system>`__ - Built on top of
   Stuart Sierra's component library, offers a set of readymade
   components.
-  `Tesla <https://github.com/otto-de/tesla-microservice>`__ - Common
   basis for some of Otto.de's Clojure microservices.

Java
^^^^

-  `Airlift <https://github.com/airlift/airlift>`__ - Framework for
   building REST services in Java.
-  `Blade <https://github.com/biezhi/blade>`__ - Modular web framework
   for Java.
-  `Dropwizard <https://dropwizard.github.io/>`__ - Java framework for
   developing ops-friendly, high-performance, RESTful web services.
-  `Jersey <https://jersey.java.net/>`__ - RESTful Web Services in Java.
   JAX-RS (JSR 311 & JSR 339) Reference Implementation.
-  `MSF4J <https://github.com/wso2/msf4j>`__ - High throughput & low
   memory footprint Java microservices framework.
-  `QBit <https://github.com/advantageous/qbit>`__ - Reactive
   programming library for building microservices.
-  `Ratpack <https://ratpack.io/>`__ - Set of Java libraries that
   facilitate fast, efficient, evolvable and well tested HTTP
   applications. specific support for the Groovy language is provided.
-  `Restlet <http://restlet.com/>`__ - Helps Java developers build web
   APIs that follow the REST architecture style.
-  `Spring Boot <http://projects.spring.io/spring-boot/>`__ - Makes it
   easy to create stand-alone, production-grade Spring based
   applications.

Scala
^^^^^

-  `Colossus <https://github.com/tumblr/colossus>`__ - I/O and
   microservice library for Scala.
-  `Finatra <http://twitter.github.io/finatra/>`__ - Fast, testable,
   Scala HTTP services built on Twitter-Server and Finagle.
-  `Play <https://www.playframework.com/>`__ - The high velocity web
   framework for Java and Scala.
-  `Scalatra <http://www.scalatra.org/>`__ - Simple, accessible and free
   web micro-framework.
-  `Skinny Micro <https://github.com/skinny-framework/skinny-micro>`__ -
   Micro-web framework to build servlet applications in Scala.
-  `Spray <http://spray.io/>`__ - Open-source toolkit for building
   REST/HTTP-based integration layers on top of Scala and Akka.

Node.js
~~~~~~~

-  `Actionhero <http://www.actionherojs.com/>`__ - Multi-transport
   Node.js API server with integrated cluster capabilities and delayed
   tasks.
-  `Baucis <https://github.com/wprl/baucis>`__ - To build and maintain
   scalable HATEOAS/Level 3 REST APIs.
-  `Express <http://expressjs.com/>`__ - Fast, unopinionated, minimalist
   web framework for Node.js
-  `Graft <https://github.com/GraftJS/graft>`__ - Full-stack javascript
   through microservices.
-  `Hapi <http://hapijs.com/>`__ - A rich framework for building
   applications and services.
-  `Koa <http://koajs.com/>`__ - Next generation web framework for
   Node.js
-  `Loopback <http://loopback.io/>`__ - Node.js framework for creating
   APIs and easily connecting to backend data sources.
-  `Micro <http://github.com/zeithq/micro>`__ - Asynchronous HTTP
   microservices.
-  `Micro-Whalla <https://github.com/czerwonkabartosz/Micro-Whalla>`__ -
   A simple, fast framework for writing microservices in Node.js
   communicate using RPC / IPC.
-  `Restify <http://restify.com/>`__ - Node.js module built specifically
   to enable you to build correct REST web services.
-  `Seneca <http://senecajs.org/>`__ - A microservices toolkit for
   Node.js
-  `Serverless <https://github.com/serverless/serverless>`__ - Build and
   maintain web, mobile and IoT applications running on AWS Lambda and
   API Gateway (formerly known as JAWS).

Perl
~~~~

-  `Mojolicious <http://mojolicio.us/>`__ - Next generation web
   framework for Perl.

Python
~~~~~~

-  `Nameko <https://github.com/onefinestay/nameko>`__ - Python framework
   for building microservices.
-  `Tornado <http://www.tornadoweb.org/>`__ - Web framework and
   asynchronous networking library.

Ruby
~~~~

-  `Hanami <https://github.com/hanami>`__ - A modern web framework for
   Ruby.
-  `Praxis <https://github.com/rightscale/praxis>`__ - Framework for
   both designing and implementing APIs.
-  `Scorched <https://github.com/wardrop/Scorched>`__ - Light-weight web
   framework for Ruby.

Capabilities
------------

API Gateways / Edge Services
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

-  `Camel <http://camel.apache.org/>`__ - Empowers you to define routing
   and mediation rules in a variety of domain-specific languages,
   including a Java-based fluent API, Spring or Blueprint XML
   configuration files, and a Scala DSL.
-  `HAProxy <http://www.haproxy.org/>`__ - Reliable, high Performance
   TCP/HTTP load balancer.
-  `Kong <https://getkong.org/>`__ - Open-source management layer for
   APIs.
-  `OpenResty <http://openresty.org/>`__ - Fast web application server
   built on top of Nginx.
-  `Tengine <http://tengine.taobao.org/>`__ - A distribution of Nginx
   with some advanced features.
-  `Træfɪk <http://traefik.io/>`__ - A modern HTTP reverse proxy and
   load balancer made to deploy microservices with ease.
-  `Tyk <https://tyk.io/>`__ - Open-source, fast and scalable API
   gateway, portal and API management platform.
-  `Vulcand <https://github.com/vulcand/vulcand>`__ - Programmatic load
   balancer backed by Etcd.
-  `Zuul <https://github.com/Netflix/zuul>`__ - An edge service that
   provides dynamic routing, monitoring, resiliency, security, and more.

Configuration and Discovery
~~~~~~~~~~~~~~~~~~~~~~~~~~~

-  `Consul <https://www.consul.io/>`__ - Service discovery and
   configuration made easy. Distributed, highly available, and
   datacenter-aware.
-  `Denominator <https://github.com/Netflix/denominator>`__ - Portably
   control DNS clouds using java or bash.
-  `Doozer <https://github.com/ha/doozerd>`__ - Highly-available,
   completely consistent store for small amounts of data. When the data
   changes, it can notify connected clients immediately.
-  `Etcd <https://github.com/coreos/etcd>`__ - Highly-available
   key-value store for shared configuration and service discovery.
-  `Eureka <https://github.com/Netflix/eureka/wiki/Eureka-at-a-glance>`__
   - REST based service that is primarily used in the AWS cloud for
   locating services for the purpose of load balancing and failover of
   middle-tier servers.
-  `Registrator <https://github.com/gliderlabs/registrator>`__ - Service
   registry bridge for Docker. Supports pluggable service registries,
   which currently includes Consul, etcd and SkyDNS 2.
-  `SkyDNS <https://github.com/skynetservices/skydns>`__ - Distributed
   service for announcement and discovery of services built on top of
   etcd. It utilizes DNS queries to discover available services.
-  `SmartStack <https://github.com/airbnb/smartstack-cookbook>`__ -
   Airbnb's automated service discovery and registration framework.
-  `Spring Cloud Config <http://cloud.spring.io/spring-cloud-config/>`__
   - Provides server and client-side support for externalized
   configuration in a distributed system.
-  `ZooKeeper <https://zookeeper.apache.org/>`__ - Open-source server
   which enables highly reliable distributed coordination.

Security
~~~~~~~~

-  `Crtauth <https://github.com/spotify/crtauth>`__ - A public key
   backed client/server authentication system.
-  `Dex <https://github.com/coreos/dex>`__ - Opinionated auth/directory
   service with pluggable connectors. OpenID Connect provider and
   third-party OAuth 2.0 delegation.
-  `JWT <http://jwt.io/>`__ - JSON Web Tokens are an open, industry
   standard RFC 7519 method for representing claims securely between two
   parties.
-  `Keycloak <https://github.com/keycloak/keycloak>`__ - Full-featured
   and extensible auth service. OpenID Connect provider and third-party
   OAuth 2.0 delegation.
-  `OAuth <http://oauth.net/2/>`__ - Provides specific authorization
   flows for web applications, desktop applications, mobile phones, and
   living room devices. Many implementations.
-  `OpenID Connect <http://openid.net/developers/libraries/>`__ -
   Libraries, products, and tools implementing current OpenID
   specifications and related specs.
-  `OSIAM <https://github.com/osiam/osiam>`__ - Open-source identity and
   access management implementing OAuth 2.0 and SCIMv2.
-  `SCIM <http://www.simplecloud.info/>`__ - System for Cross-domain
   Identity Management.
-  `Vault <https://www.vaultproject.io/>`__ - Secures, stores, and
   tightly controls access to tokens, passwords, certificates, API keys,
   and other secrets in modern computing.

Elasticity
~~~~~~~~~~

-  `Chronos <https://github.com/mesos/chronos>`__ - Fault tolerant job
   scheduler for Mesos which handles dependencies and ISO8601 based
   schedules.
-  `Fenzo <https://github.com/Netflix/Fenzo>`__ - Extensible scheduler
   for Mesos frameworks.
-  `Galaxy <http://www.paralleluniverse.co/galaxy/>`__ - Open-source
   high-performance in-memory data-grid.
-  `Grape <http://reverbrain.com/grape/>`__ - Realtime processing
   pipeline.
-  `Hazelcast <http://hazelcast.org/>`__ - Open-source in-memory
   data-grid. Allows you to distribute data and computation across
   servers, clusters and geographies, and to manage very large data sets
   or high data ingest rates. Mature technology.
-  `Helix <http://helix.apache.org/>`__ - Generic cluster management
   framework used for the automatic management of partitioned,
   replicated and distributed resources hosted on a cluster of nodes.
-  `Ignite <http://ignite.apache.org/>`__ - High-performance, integrated
   and distributed in-memory platform for computing and transacting on
   large-scale data sets in real-time, orders of magnitude faster than
   possible with traditional disk-based or flash technologies.
-  `Marathon <https://mesosphere.github.io/marathon/>`__ - Deploy and
   manage containers (including Docker) on top of Apache Mesos at scale.
-  `Mesos <https://mesos.apache.org/>`__ - Abstracts CPU, memory,
   storage, and other compute resources away from machines (physical or
   virtual), enabling fault-tolerant and elastic distributed systems to
   easily be built and run effectively.
-  `Nomad <https://www.nomadproject.io/>`__ - Distributed, highly
   available, datacenter-aware scheduler.
-  `Onyx <https://github.com/onyx-platform/onyx>`__ - Distributed,
   masterless, high performance, fault tolerant data processing for
   Clojure.
-  `Ordasity <https://github.com/boundary/ordasity>`__ - Designed to
   spread persistent or long-lived workloads across several machines.
-  `Redisson <https://github.com/mrniko/redisson>`__ - Distributed and
   scalable Java data structures on top of Redis server.

Messaging
~~~~~~~~~

-  `ØMQ <http://zeromq.org/>`__ - Brokerless intelligent transport
   layer.
-  `ActiveMQ <http://activemq.apache.org/>`__ - Powerful open-source
   messaging and integration patterns server.
-  `Aeron <https://github.com/real-logic/Aeron>`__ - Efficient reliable
   UDP unicast, UDP multicast, and IPC message transport.
-  `Apollo <http://activemq.apache.org/apollo/>`__ - Faster, more
   reliable, easier to maintain messaging broker built from the
   foundations of the original ActiveMQ.
-  `Ascoltatori <https://github.com/mcollina/ascoltatori>`__ - Pub/sub
   library for Node.
-  `Beanstalk <http://kr.github.io/beanstalkd/>`__ - Simple, fast work
   queue.
-  `Disque <https://github.com/antirez/disque>`__ - Distributed message
   broker.
-  `Kafka <http://kafka.apache.org/>`__ - Publish-subscribe messaging
   rethought as a distributed commit log.
-  `Malamute <https://github.com/zeromq/malamute>`__ - ZeroMQ enterprise
   messaging broker.
-  `Mosca <http://www.mosca.io/>`__ - MQTT broker as a module.
-  `Mosquitto <http://mosquitto.org/>`__ - Open-source message broker
   that implements the MQTT protocol.
-  `Nanomsg <http://nanomsg.org/>`__ - Socket library that provides
   several common communication patterns for building distributed
   systems.
-  `NATS <https://nats.io/>`__ - Open-source, high-performance,
   lightweight cloud messaging system.
-  `NSQ <http://nsq.io/>`__ - A realtime distributed messaging platform.
-  `Qpid <https://qpid.apache.org/>`__ - Cross-platform messaging
   components built on AMQP.
-  `RabbitMQ <https://www.rabbitmq.com/>`__ - Open-source Erlang-based
   message broker that just works.

Serialization
~~~~~~~~~~~~~

-  `BooPickle <https://github.com/ochrons/boopickle>`__ - Binary
   serialization library for efficient network communication. For Scala
   and Scala.js
-  `Cap’n Proto <https://capnproto.org/>`__ - Insanely fast data
   interchange format and capability-based RPC system.
-  `CBOR <http://cbor.io/>`__ - Implementations of the CBOR standard
   (RFC 7049) in many languages.
-  `Cereal <http://uscilab.github.io/cereal/>`__ - C++11 library for
   serialization.
-  `Cheshire <https://github.com/dakrone/cheshire>`__ - Clojure JSON and
   JSON SMILE encoding/decoding.
-  `Etch <http://etch.apache.org/>`__ - Cross-platform, language and
   transport-independent framework for building and consuming network
   services.
-  `Fastjson <https://github.com/alibaba/fastjson>`__ - Fast JSON
   Processor.
-  `Ffjson <https://github.com/pquerna/ffjson>`__ - Faster JSON
   serialization for Go.
-  `FST <https://github.com/RuedigerMoeller/fast-serialization>`__ -
   Fast java serialization drop in-replacemen.
-  `Jackson <https://github.com/FasterXML/jackson>`__ - A multi-purpose
   Java library for processing JSON data format.
-  `Jackson
   Afterburner <https://github.com/FasterXML/jackson-module-afterburner>`__
   - Jackson module that uses bytecode generation to further speed up
   data binding (+30-40% throughput for serialization, deserialization).
-  `Kryo <https://github.com/EsotericSoftware/kryo>`__ - Java
   serialization and cloning: fast, efficient, automatic.
-  `MessagePack <http://msgpack.org/>`__ - Efficient binary
   serialization format.
-  `Protostuff <http://www.protostuff.io/>`__ - A serialization library
   with built-in support for forward-backward compatibility (schema
   evolution) and validation.
-  `SBinary <https://github.com/harrah/sbinary>`__ - Library for
   describing binary formats for Scala types.
-  `Thrift <http://thrift.apache.org/>`__ - The Apache Thrift software
   framework, for scalable cross-language services development.

Storage
~~~~~~~

-  `Aerospike <http://www.aerospike.com/>`__ - High performance NoSQL
   database delivering speed at scale.
-  `ArangoDB <https://www.arangodb.com/>`__ - A distributed free and
   open-source database with a flexible data model for documents,
   graphs, and key-values.
-  `Couchbase <http://www.couchbase.com/>`__ - A distributed database
   engineered for performance, scalability, and simplified
   administration.
-  `Crate <https://crate.io/>`__ - Scalable SQL database with the NoSQL
   goodies.
-  `Datomic <http://www.datomic.com/>`__ - Fully transactional,
   cloud-ready, distributed database.
-  `Druid <http://druid.io/>`__ - Fast column-oriented distributed data
   store.
-  `Elasticsearch <https://www.elastic.co/products/elasticsearch>`__ -
   Open-source distributed, scalable, and highly available search
   server.
-  `Elliptics <http://reverbrain.com/elliptics/>`__ - Fault tolerant
   distributed key/value storage.
-  `Geode <http://geode.incubator.apache.org/>`__ - Open-source,
   distributed, in-memory database for scale-out applications.
-  `MemSQL <http://www.memsql.com/>`__ - High-performance, in-memory
   database that combines the horizontal scalability of distributed
   systems with the familiarity of SQL.
-  `Reborn <https://github.com/reborndb/reborn>`__ - Distributed
   database fully compatible with redis protocol.
-  `RethinkDB <http://rethinkdb.com/>`__ - Open-source, scalable
   database that makes building realtime apps easier.
-  `Secure Scuttlebutt <https://github.com/ssbc/docs>`__ - P2P database
   of message-feeds.
-  `Tachyon <http://tachyon-project.org/>`__ - Memory-centric
   distributed storage system, enabling reliable data sharing at
   memory-speed across cluster frameworks.

Reactivity
~~~~~~~~~~

-  `Reactive Kafka <https://github.com/softwaremill/reactive-kafka>`__ -
   Reactive Streams API for Apache Kafka.
-  `ReactiveX <http://reactivex.io/>`__ - API for asynchronous
   programming with observable streams. Available for idiomatic Java,
   Scala, C#, C++, Clojure, JavaScript, Python, Groovy, JRuby, and
   others.
-  `Simple React <https://github.com/aol/simple-react>`__ - Powerful
   future streams & asynchronous data structures for Java 8.

Resilience
~~~~~~~~~~

-  `Hystrix <https://github.com/Netflix/Hystrix>`__ - Latency and fault
   tolerance library designed to isolate points of access to remote
   systems, services and 3rd party libraries, stop cascading failure and
   enable resilience in complex distributed systems where failure is
   inevitable.
-  `Pathod <http://pathod.net/>`__ - Crafted malice for tormenting HTTP
   clients and servers.
-  `Raft Consensus <http://raftconsensus.github.io/>`__ - Consensus
   algorithm that is designed to be easy to understand. It's equivalent
   to Paxos in fault-tolerance and performance.
-  `Resilient HTTP <http://resilient-http.github.io/>`__ - A smart HTTP
   client with super powers like fault tolerance, dynamic server
   discovery, auto balancing and reactive recovery, designed for
   distributed systems.
-  `Saboteur <https://github.com/tomakehurst/saboteur>`__ - Causing
   deliberate network mayhem for better resilience.
-  `Simian Army <https://github.com/Netflix/SimianArmy>`__ - Suite of
   tools for keeping your cloud operating in top form. Chaos Monkey, the
   first member, is a resiliency tool that helps ensure that your
   applications can tolerate random instance failures.

Testing
~~~~~~~

-  `Mitmproxy <https://mitmproxy.org/>`__ - An interactive console
   program that allows traffic flows to be intercepted, inspected,
   modified and replayed.
-  `Mountebank <http://www.mbtest.org/>`__ - Cross-platform,
   multi-protocol test doubles over the wire.
-  `VCR <https://github.com/vcr/vcr>`__ - Record your test suite's HTTP
   interactions and replay them during future test runs for fast,
   deterministic, accurate tests. See the list of ports for
   implementations in other languages.
-  `Wilma <https://github.com/epam/Wilma>`__ - Combined HTTP/HTTPS
   service stub and transparent proxy solution.
-  `WireMock <http://wiremock.org/>`__ - Flexible library for stubbing
   and mocking web services. Unlike general purpose mocking tools it
   works by creating an actual HTTP server that your code under test can
   connect to as it would a real web service.

Monitoring and Debugging
~~~~~~~~~~~~~~~~~~~~~~~~

-  `Beats <https://www.elastic.co/products/beats>`__ - Lightweight
   shippers for Elasticsearch & Logstash.
-  `Collectd <https://collectd.org/>`__ - The system statistics
   collection daemon.
-  `Elastalert <https://github.com/yelp/elastalert>`__ - Easy & flexible
   alerting for Elasticsearch.
-  `Grafana <http://grafana.org/>`__ - An open-source, feature rich
   metrics dashboard and graph editor for Graphite, InfluxDB & OpenTSDB.
-  `Graphite <http://graphite.wikidot.com/>`__ - Scalable realtime
   graphing.
-  `Prometheus <http://prometheus.io/>`__ - An open-source service
   monitoring system and time series database.
-  `Riemann <http://riemann.io/>`__ - Monitors distributed systems.
-  `Sensu <https://github.com/sensu>`__ - Monitoring for today's
   infrastructure.
-  `Trace <https://github.com/RisingStack/trace-nodejs>`__ - A
   visualised stack trace platform designed for microservices.
-  `Watcher <https://www.elastic.co/products/watcher>`__ - Alerting for
   Elasticsearch.
-  `Zabbix <http://www.zabbix.com/>`__ - Open-source enterprise-class
   monitoring solution.

Logging
~~~~~~~

-  `Fluentd <http://www.fluentd.org/>`__ - Open-source data collector
   for unified logging layer.
-  `Graylog <https://www.graylog.org/>`__ - Fully integrated open-source
   log management platform.
-  `Kibana <https://www.elastic.co/products/kibana>`__ - Flexible
   analytics and visualization platform.
-  `Logstash <https://www.elastic.co/products/logstash>`__ - Tool for
   managing events and logs.
-  `Suro <https://github.com/Netflix/suro/wiki>`__ - Distributed data
   pipeline which enables services for moving, aggregating, routing,
   storing data.

IT Automation / Provisioning
----------------------------

-  `Ansible <http://www.ansible.com/>`__ - Radically simple IT
   automation platform that makes your applications and systems easier
   to deploy.
-  `Chef <https://www.chef.io/chef/>`__ - Automate how you build,
   deploy, and manage your infrastructure.
-  `Helios <https://github.com/spotify/helios>`__ - Docker container
   orchestration platform.
-  `Otto <https://www.ottoproject.io/>`__ - Development and deployment
   made easy.
-  `Packer <https://www.packer.io/>`__ - Tool for creating identical
   machine images for multiple platforms from a single source
   configuration.
-  `Puppet <https://puppetlabs.com/>`__ - From provisioning bare metal &
   launching containers to new ways to manage infrastructure as code.
-  `Salt <https://github.com/saltstack/salt>`__ - Infrastructure
   automation and management system.
-  `Terraform <https://www.terraform.io/>`__ - Provides a common
   configuration to launch infrastructure, from physical and virtual
   servers to email and DNS providers.

Deployment and Continuous Integration
-------------------------------------

On-prem
~~~~~~~

-  `ION-Roller <https://github.com/gilt/ionroller>`__ - AWS immutable
   deployment framework for web services.
-  `Janky <https://github.com/github/janky>`__ - Continuous integration
   server built on top of Jenkins and Hubot.
-  `Jenkins <http://jenkins-ci.org/>`__ - Extensible open-source
   continuous integration server.
-  `Nscale <https://github.com/nearform/nscale>`__ - Open toolkit
   supporting configuration, build and deployment of connected container
   sets.
-  `Project 6 <https://github.com/DatawiseIO/Project6>`__ - Software for
   deploying and managing Docker containers across a cluster of hosts,
   with a focus on simplifying network and storage configurations for
   on-premises environments.
-  `Rancher <https://github.com/rancher/rancher>`__ - Open-source
   platform for operating Docker in production.
-  `RPM Maven <http://mojo.codehaus.org/rpm-maven-plugin/>`__ - Allows
   artifacts from one or more projects to be packaged in an RPM for
   distribution.

Hosted
~~~~~~

-  `AWS CodeDeploy <http://aws.amazon.com/codedeploy/>`__ - Deployment
   service that enables developers to automate the deployment of
   applications to instances and to update the applications as required.
-  `AWS OpsWorks <http://aws.amazon.com/opsworks/>`__ - Provides a
   simple and flexible way to create and manage stacks and applications.
-  `Codeship <https://codeship.com/>`__ - Hosted continuous delivery
   platform that takes care of the testing and deployment process.
-  `Travis <https://travis-ci.org/>`__ - Continuous integration and
   deployment service.

Lightweight
~~~~~~~~~~~

-  `Capsule <https://github.com/puniverse/capsule>`__ - Packaging and
   deployment tool for JVM applications.
-  `Kafka Deploy <https://github.com/nathanmarz/kafka-deploy>`__ -
   Automated deploy for a Kafka cluster on AWS.

Containers
----------

-  `AWS ECS <http://aws.amazon.com/ecs/>`__ - Easily run and manage
   Docker-enabled applications across a cluster of Amazon EC2 instances.
-  `CoreOS <https://coreos.com/>`__ - Open-source lightweight operating
   system based on the Linux kernel and designed for providing
   infrastructure to clustered deployments.
-  `Docker <https://www.docker.com/>`__ - Open platform for distributed
   applications for developers and sysadmins.
-  `Kubernetes <http://kubernetes.io/>`__ - Open-source orchestration
   system for Docker containers.
-  `Linux Containers <https://linuxcontainers.org/>`__ - The umbrella
   project behind LXC, LXD, LXCFS and CGManager.
-  `RancherOS <https://github.com/rancher/os>`__ - The smallest, easiest
   way to run Docker in production at scale.

Documentation & Modeling
------------------------

REST APIs
~~~~~~~~~

-  `Aglio <https://github.com/danielgtaylor/aglio>`__ - API Blueprint
   renderer with theme support that outputs static HTML.
-  `API Blueprint <https://apiblueprint.org/>`__ - Tools for your whole
   API lifecycle. Use it to discuss your API with others. Generate
   documentation automatically. Or a test suite. Or even some code.
-  `Apidoc <https://github.com/mbryzek/apidoc>`__ - Beautiful
   documentation for REST services.
-  `RAML <http://raml.org/>`__ - RESTful API Modeling Language, a simple
   and succinct way of describing practically-RESTful APIs.
-  `Slate <https://github.com/tripit/slate>`__ - Beautiful static
   documentation for your API.
-  `Spring REST Docs <http://projects.spring.io/spring-restdocs/>`__ -
   Document RESTful services by combining hand-written documentation
   with auto-generated snippets produced with Spring MVC Test.
-  `Swagger <http://swagger.io/>`__ - A simple yet powerful
   representation of your RESTful API.

Standards / Recommendations
---------------------------

World Wide Web
~~~~~~~~~~~~~~

-  `W3C.REC-Webarch <http://www.w3.org/TR/webarch/>`__ - Architecture of
   the World Wide Web, Volume One.
-  `RFC3986 <https://tools.ietf.org/html/rfc3986>`__ - Uniform Resource
   Identifier (URI): Generic Syntax.
-  `RFC6570 <https://tools.ietf.org/html/rfc6570>`__ - URI Template.
-  `RFC7320 <https://tools.ietf.org/html/rfc7320>`__ - URI Design and
   Ownership.

HTTP/1.1
~~~~~~~~

-  `RFC7230 <https://tools.ietf.org/html/rfc7230>`__ - Message Syntax
   and Routing.
-  `RFC7231 <https://tools.ietf.org/html/rfc7231>`__ - Semantics and
   Content.
-  `RFC7232 <https://tools.ietf.org/html/rfc7232>`__ - Conditional
   Requests.
-  `RFC7233 <https://tools.ietf.org/html/rfc7233>`__ - Range Requests.
-  `RFC7234 <https://tools.ietf.org/html/rfc7234>`__ - Caching.
-  `RFC7235 <https://tools.ietf.org/html/rfc7235>`__ - Authentication.

HTTP/2
~~~~~~

-  `RFC7540 <https://tools.ietf.org/html/rfc7540>`__ - Hypertext
   Transfer Protocol Version 2.

RPC
~~~

-  `BERT-RPC 1.0 <http://bert-rpc.org/>`__ - An attempt to specify a
   flexible binary serialization and RPC protocol that are compatible
   with the philosophies of dynamic languages.
-  `JSON-RPC 2.0 <http://www.jsonrpc.org/specification>`__ - A
   stateless, light-weight remote procedure call (RPC) protocol.

Messaging
~~~~~~~~~

-  `AMQP <http://www.amqp.org/>`__ - Advanced Message Queuing Protocol.
-  `MQTT <http://mqtt.org/>`__ - MQ Telemetry Transport.
-  `STOMP <https://stomp.github.io/>`__ - Simple Text Oriented Messaging
   Protocol.

Security
~~~~~~~~

-  `RFC5246 <http://tools.ietf.org/html/rfc5246>`__ - The Transport
   Layer Security (TLS) Protocol Version 1.2.
-  `RFC6066 <http://tools.ietf.org/html/rfc6066>`__ - TLS Extensions.
-  `RFC6749 <http://tools.ietf.org/html/rfc6749>`__ - The OAuth 2.0
   authorization framework.
-  `RFC7515 <https://tools.ietf.org/html/rfc7515>`__ - JSON Web
   Signature (JWS) represents content secured with digital signatures or
   Message Authentication Codes (MACs) using JSON-based data structures.
-  `RFC7519 <https://tools.ietf.org/html/rfc7519>`__ - JSON Web Token
   (JWT) is a compact, URL-safe means of representing claims to be
   transferred between two parties.
-  `RFC7642 <https://tools.ietf.org/html/rfc7642>`__ - SCIM:
   Definitions, overview, concepts, and requirements.
-  `RFC7643 <https://tools.ietf.org/html/rfc7643>`__ - SCIM: Core
   Schema, provides a platform-neutral schema and extension model for
   representing users and groups.
-  `RFC7644 <https://tools.ietf.org/html/rfc7644>`__ - SCIM: Protocol,
   an application-level, REST protocol for provisioning and managing
   identity data on the web.
-  `OIDCONN <http://openid.net/connect/>`__ - OpenID Connect 1.0 is a
   simple identity layer on top of the OAuth 2.0 protocol. It allows
   clients to verify the identity of the end-user based on the
   authentication performed by an Authorization Server, as well as to
   obtain basic profile information about the end-user in an
   interoperable and REST-like manner.

Service Discovery
~~~~~~~~~~~~~~~~~

-  `HAL-DRAFT <https://tools.ietf.org/html/draft-kelly-json-hal-07>`__ -
   The JSON Hypertext Application Language (HAL) is a standard which
   establishes conventions for expressing hypermedia controls, such as
   links, with JSON. DRAFT
-  `WADL <http://www.w3.org/Submission/wadl/>`__ - The Web Application
   Description Language specification.
-  `WSDL <http://www.w3.org/TR/wsdl20/>`__ - The Web Services
   Description Language Version 2.0 spec.

Data Formats
~~~~~~~~~~~~

-  `RFC4627 <https://tools.ietf.org/html/rfc4627>`__ - JavaScript Object
   Notation (JSON).
-  `RFC7049 <http://tools.ietf.org/search/rfc7049>`__ - Concise Binary
   Object Representation (CBOR).
-  `BSON <http://bsonspec.org/>`__ - Bin­ary JSON (BSON).
-  `SBE <https://github.com/FIXTradingCommunity/fix-simple-binary-encoding>`__
   - Simple Binary Encoding (SBE).
-  `SMILE <http://wiki.fasterxml.com/SmileFormatSpec>`__ -
   JSON-compatible binary data format.
-  `MSGPACK <https://github.com/msgpack/msgpack/blob/master/spec.md>`__
   - MessagePack Specification.

Unicode
~~~~~~~

-  `UNIV8 <http://www.unicode.org/versions/Unicode8.0.0/>`__ - The
   Unicode Consortium. The Unicode Standard, Version 8.0.0, (Mountain
   View, CA: The Unicode Consortium, 2015. ISBN 978-1-936213-10-8).
-  `RFC3629 <https://tools.ietf.org/html/rfc3629>`__ - UTF-8, a
   transformation format of ISO 10646.

Real Life Stories
-----------------

-  `Clean microservice
   architecture <http://blog.cleancoder.com/uncle-bob/2014/10/01/CleanMicroserviceArchitecture.html>`__
-  `Failing at
   microservices <https://rclayton.silvrback.com/failing-at-microservices>`__
-  `How to talk to your friends about
   microservices <https://blog.pivotal.io/labs/labs/how-to-talk-to-your-friends-about-microservices>`__
-  `How we build microservices at
   Karma <https://blog.yourkarma.com/building-microservices-at-karma>`__
-  `How we ended up with microservices at
   SoundCloud <http://philcalcado.com/2015/09/08/how_we_ended_up_with_microservices.html>`__
-  `Microservices: lessons from the
   frontline <https://www.thoughtworks.com/insights/blog/microservices-lessons-frontline>`__
-  `Monolith first <http://martinfowler.com/bliki/MonolithFirst.html>`__
-  `Scaling microservices at Gilt with Scala, Docker and
   AWS <http://www.infoq.com/news/2015/04/scaling-microservices-gilt>`__

Theory
------

Articles & Papers
~~~~~~~~~~~~~~~~~

-  `AKF Scale
   Cube <http://akfpartners.com/techblog/2008/05/08/splitting-applications-or-services-for-scale/>`__
   - Model depicting the dimensions to scale a service.
-  `CALM <http://db.cs.berkeley.edu/papers/cidr11-bloom.pdf>`__ -
   Consistency as logical monotonicity. :small\_orange\_diamond:PDF
-  `Canary Release <http://martinfowler.com/bliki/CanaryRelease.html>`__
   - Technique to reduce the risk of introducing a new software version
   in production by slowly rolling out the change to a small subset of
   users before rolling it out to the entire infrastructure and making
   it available to everybody.
-  `CAP
   Theorem <http://blog.thislongrun.com/2015/03/the-cap-theorem-series.html>`__
   - States that it is impossible for a distributed computer system to
   simultaneously provide all three of the following guarantees:
   Consistency, Availability and Partition tolerance.
-  `Cloud Design
   Patterns <https://msdn.microsoft.com/en-us/library/dn600223.aspx>`__
   - Contains twenty-four design patterns that are useful in
   cloud-hosted applications. Includes: Circuit Breaker, Competing
   Consumers, CQRS, Event Sourcing, Gatekeeper, Cache-Aside, etc.
-  `Hexagonal
   Architecture <http://alistair.cockburn.us/Hexagonal+architecture>`__
   - Allows an application to equally be driven by users, programs,
   automated test or batch scripts, and to be developed and tested in
   isolation from its eventual run-time devices and databases.
-  `Microservice
   Architecture <http://martinfowler.com/articles/microservices.html>`__
   - Particular way of designing software applications as suites of
   independently deployable services.
-  `Microservices and
   SOA <http://www.oracle.com/technetwork/issue-archive/2015/15-mar/o25architect-2458702.html>`__
   - Similarities, differences, and where we go from here.
-  `Microservices
   RefCard <https://dzone.com/refcardz/getting-started-with-microservices>`__
   - Getting started with microservices.
-  `Microservices
   Trade-Offs <http://martinfowler.com/articles/microservice-trade-offs.html>`__
   - Guide to ponder costs and benefits of the mircoservices
   architectural style.
-  `Reactive Manifesto <http://www.reactivemanifesto.org/>`__ - Reactive
   systems definition.
-  `Reactive Streams <http://www.reactive-streams.org/>`__ - Initiative
   to provide a standard for asynchronous stream processing with
   non-blocking back pressure.
-  `ROCAS <http://resources.1060research.com/docs/2015/Resource-Oriented-Computing-Adaptive-Systems-ROCAS-1.2.pdf>`__
   - Resource Oriented Computing for Adaptive Systems.
   :small\_orange\_diamond:PDF
-  `SECO <http://ceur-ws.org/Vol-746/IWSECO2011-6-DengYu.pdf>`__ -
   Understanding software ecosystems: a strategic modeling approach.
   :small\_orange\_diamond:PDF
-  `Service Discovery in a Microservice
   Architecture <https://www.nginx.com/blog/service-discovery-in-a-microservices-architecture/>`__
   - Overview of discovery and registration patterns.
-  `Testing Strategies in a Microservice
   Architecture <http://martinfowler.com/articles/microservice-testing/>`__
   - Approaches for managing the additional testing complexity of
   multiple independently deployable components.
-  `Your Server as a Function <http://monkey.org/~marius/funsrv.pdf>`__
   - Describes three abstractions which combine to present a powerful
   programming model for building safe, modular, and efficient server
   software: Composable futures, services and filters.
   :small\_orange\_diamond:PDF

Tutorials
~~~~~~~~~

-  `Microservices without the
   Servers <https://aws.amazon.com/blogs/compute/microservices-without-the-servers/>`__
   - Step by step demo-driven talk about serverless architecture.
-  Microservices in C#: `Part
   1 <http://insidethecpu.com/2015/07/17/microservices-in-c-part-1-building-and-testing/>`__,
   `Part
   2 <http://insidethecpu.com/2015/07/31/microservices-in-c-part-2-consistent-message-delivery/>`__,
   `Part
   3 <http://insidethecpu.com/2015/08/14/microservices-in-c-part-3-queue-pool-sizing/>`__,
   `Part
   4 <http://insidethecpu.com/2015/08/28/microservices-in-c-part-4-scaling-out/>`__,
   `Part
   5 <http://insidethecpu.com/2015/09/11/microservices-in-c-part-5-autoscaling/>`__.
-  `Using Packer and Ansible to build immutable
   infrastructure <https://blog.codeship.com/packer-ansible/>`__

Books
~~~~~

-  `Building
   Microservices <https://www.nginx.com/wp-content/uploads/2015/01/Building_Microservices_Nginx.pdf>`__
   - Building Microservices: Designing Fine-grained Systems. Sam Newman.
   Preview Edition. :small\_orange\_diamond:PDF
-  `Migrating to Cloud Native Application
   Architectures <http://pivotal.io/platform/migrating-to-cloud-native-application-architectures-ebook>`__
   - This O’Reilly report defines the unique characteristics of cloud
   native application architectures such as microservices and
   twelve-factor applications.
-  `The Art of Scalability <http://theartofscalability.com/>`__ - The
   Art of Scalability: Scalable Web Architecture, Processes, and
   Organizations for the Modern Enterprise. Martin L. Abbott, Michael T.
   Fisher.

Sites
~~~~~

-  `Microservices Resource
   Guide <http://martinfowler.com/microservices/>`__ - Martin Fowler's
   choice of articles, videos, books, and podcasts that can teach you
   more about the microservices architectural style.
-  `Microservice Patterns <http://microservices.io/>`__ - Microservice
   architecture patterns and best practices.

Emerging Technologies
---------------------

-  `Blockchain ID <https://github.com/blockstack/blockchain-id/wiki>`__
   - A unique identifier that is secured by a blockchain. Blockchain IDs
   are simultaneously secure, human-meaningful, and decentralized,
   thereby squaring Zooko's triangle.
-  `Blocknet <http://blocknet.co/>`__ - The Blocknet makes possible to
   deliver microservices over a blockchain-based P2P network
   architecture.
-  `Edgware Fabric <http://edgware-fabric.org/>`__ - Lightweight, agile
   service bus for systems at the edge of the network, in the physical
   world.
-  `MultiChain <http://www.multichain.com/>`__ - Open platform for
   building blockchains.
-  `Node-RED <http://nodered.org/>`__ - Visual tool for wiring together
   hardware devices, APIs and online services in new and interesting
   ways.
-  `Pony <http://www.ponylang.org/>`__ - Open-source, object-oriented,
   actor-model, capabilities-secure, high performance programming
   language.

License
-------

|CC0|

Contributing
------------

Please, read the `Contribution
Guidelines <https://github.com/mfornos/awesome-microservices/blob/master/CONTRIBUTING.md>`__
before submitting your suggestion.

Feel free to `open an
issue <https://github.com/mfornos/awesome-microservices/issues>`__ or
`create a pull
request <https://github.com/mfornos/awesome-microservices/pulls>`__ with
your additions.

:star2: Thank you!

Acknowledgments
---------------

Table of contents generated with
`DocToc <https://github.com/thlorenz/doctoc>`__

.. |Awesome| image:: https://cdn.rawgit.com/sindresorhus/awesome/d7305f38d29fed78fa85652e3a63e154dd8e8829/media/badge.svg
   :target: https://github.com/sindresorhus/awesome
.. |CC0| image:: http://i.creativecommons.org/p/zero/1.0/88x31.png
   :target: http://creativecommons.org/publicdomain/zero/1.0/
