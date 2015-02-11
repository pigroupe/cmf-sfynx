package sfynx

import io.gatling.core.Predef._
import io.gatling.http.Predef._
import scala.concurrent.duration._

class SymfonyConnexion extends Simulation {

    val httpConf = http
        .baseURL("http://sfynx.optim")
        .acceptCharsetHeader("ISO-8859-1,utf-8;q=0.7,*;q=0.7")
        .acceptHeader("""text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8""")
        .acceptEncodingHeader("gzip, deflate")
        .acceptLanguageHeader("fr,fr-fr;q=0.8,en-us;q=0.5,en;q=0.3")
        .disableFollowRedirect

    val headers_1 = Map(
        "Keep-Alive" -> "115")

    val headers_2 = Map(
        "Accept" -> "application/json, text/javascript, */*; q=0.01",
        "Keep-Alive" -> "timeout=5, max=100",
        "X-Requested-With" -> "XMLHttpRequest",
                "Content-Type" -> """application/json""")

    val scn = scenario("Scenario name")
        .group("Page fr") {
            exec(http("request_fr_1")
                    .get("/fr/")
                    .headers(headers_1)
                    .check(status.is(200)))
            .pause(1000 milliseconds, 3000 milliseconds)
            .exec(http("request_fr_2")
                    .get("/fr/404error")
                    .headers(headers_1)
                    .check(status.is(200)))
            .pause(1000 milliseconds, 3000 milliseconds)
            .exec(http("request_fr_3")
                    .get("/fr/")
                    .headers(headers_1)
                    .check(status.is(200)))
            .pause(1000 milliseconds, 3000 milliseconds)
        }
        .group("Page en") {
            exec(http("request_en_1")
                    .get("/en/")
                    .headers(headers_1)
                    .check(status.is(200)))
            .pause(1000 milliseconds, 3000 milliseconds)
            .exec(http("request_en_2")
                    .get("/en/404error")
                    .headers(headers_1)
                    .check(status.is(200)))
            .pause(1000 milliseconds, 3000 milliseconds)
            .exec(http("request_en_3")
                    .get("/en/")
                    .headers(headers_1)
                    .check(status.is(200)))
            .pause(1000 milliseconds, 3000 milliseconds)
        }
        .group("Login") {
            exec(http("request_login_1")
                    .get("/")
                    .headers(headers_1))
                .pause(0 milliseconds, 100 milliseconds)
                .feed(csv("connexion_sfynx.csv"))
                .exec(http("request_login_2")
                    .post("/login_check")
                    .headers(headers_2)
                    .formParam("_username","${username}")
                    .formParam("_password","${password}")
                    .check(status.in(200 to 302)))
                .pause(0 milliseconds, 100 milliseconds)
        }
        .group("Logout") {
            repeat(1) {
                exec(http("request_logout_1")
                        .get("/")
                        .headers(headers_1))
                    .pause(1000 milliseconds, 3000 milliseconds)
            }.exec(http("request_logout_2")
                    .get("/logout")
                    .headers(headers_1)
                    .check(status.in(200 to 302)))
            .pause(0 milliseconds, 100 milliseconds)
            .exec(http("request_logout_3")
                    .get("/")
                    .headers(headers_1))
            .pause(0 milliseconds, 100 milliseconds)
        }

    setUp(scn.inject(rampUsers(500) over (1 seconds)).protocols(httpConf))
}