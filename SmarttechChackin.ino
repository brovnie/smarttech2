#include <ESP8266WiFi.h>;

//Call to ESpressif SDK
extern "C" {
#include <user_interface.h>
}

const char* ssid     = "internet"; 
const char* password = "password";

uint8_t mac[6] {0xxx, 0xxx, 0xxx, 0xxx, 0xxx, 0xxx};
const char* host = "***.***.*.***";

int buttonState1 = 0;
int buttonState2 = 0;
int buttons[] = {14, 13};
int ledPins[] = {12, 5, 0, 16};
int buzzer = 2;

String data;
int digit;
String code;
String codeStatus;

void setup() {
  Serial.begin(115200);
  Serial.println();

  //De volgende lijn veranderd het MAC adres van de ESP8266
  wifi_set_macaddr(0, const_cast<uint8*>(mac));

  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);

  Serial.print("Bezig met connecteren...");
  while (WiFi.status() != WL_CONNECTED)
  {
    delay(500);
    Serial.print(".");
  }
  Serial.println();

  Serial.print("Geconnecteerd!\nMijn IP adres: ");
  Serial.println(WiFi.localIP());
  Serial.printf("Mijn MAC adres is: %s\n", WiFi.macAddress().c_str());
  Serial.print("De gateway is: ");
  Serial.println(WiFi.gatewayIP());
  Serial.print("De DNS is: ");
  Serial.println(WiFi.dnsIP());

  //Set up buttons,leds & buzzer
  for (int i = 0; i < 4; i++)
  {
    pinMode(ledPins[i], OUTPUT);
  }
  for (int i = 0; i < 2; i++ ) {
    pinMode(buttons[i], INPUT);
  }
  pinMode(buzzer, OUTPUT);
}



void loop() {

  //
  // 1. CREATE CODE
  buttonState1 = digitalRead(buttons[0]);
  buttonState2 = digitalRead(buttons[1]);

  data = "?code=";

  // 1a) BUTTON 1
  if (buttonState1 == HIGH) {
    //...ones, turn led on!
    digitalWrite(ledPins[0], LOW);
    digit = 1;
    delay(300);
  } else {
    digitalWrite(ledPins[0], HIGH);
  }
  // 1a) BUTTON 2
  if (buttonState2 == HIGH) {
    //...ones, turn led on!
    digitalWrite(ledPins[1], LOW);
    digit = 2;
    delay(300);
  } else {
    digitalWrite(ledPins[1], HIGH);
  }
  // 1b) CREATE CODE
  if (digit > 0) {
    Serial.println(digit);
    code.concat(digit);
    Serial.print(code);

    //clean
    if (code.length() == 4 ) {
      data.concat(code);
      code = "";
      Serial.println(data);
    }
    digit = 0;
  }

  //
  // 2. CONNECTIE MET WEBSITE
  if (data.length() > 7) {
    digitalWrite(ledPins[0], LOW);
    digitalWrite(ledPins[1], LOW);

    //
    //  2.a) STUUR CODE NAAR DATABASE
    Serial.print("connecting to ");
    Serial.println(host);

    // Probeer te connecteren met de host
    WiFiClient client;
    client.setTimeout(1000);    //Nodig voor de timeout in readStringUntil
    const int httpPort = 80;    //Een webserver luistert op poort 80
    if (!client.connect(host, httpPort)) {
      Serial.println("connection failed");
      return;
    }

    // Het path klaar maken van hetgeen we willen van de host
    String url = "/smarttech/add.php";
    Serial.print("Requesting URL: ");
    Serial.println(url);

    // Code naar de host sturen:
    client.print(String("GET ") + url + data + " HTTP/1.1\r\n" +
                 "Host: " + host + "\r\n" +
                 "Connection: close\r\n\r\n");
    delay(500);

    // Alles lezen en afprinten naar de seriele poort.
    // Merk op dat ook de antwoord headers worden afgedrukt!
    while (client.available()) {
      String line = client.readStringUntil('\n');
      Serial.println(line);
    }

    // De verbinding met de server sluiten
    Serial.println();
    Serial.println("closing connection with " + url  );
    client.stop();
    delay(10000);

    //
    // 2.b) VALIDATE CODE -> GET RESPONSE FROM WEBSITE
    client.setTimeout(1000);    //Nodig voor de timeout in readStringUntil

    if (!client.connect(host, httpPort)) {
      Serial.println("connection failed");
      return;
    }

    // Het path klaar maken van hetgeen we willen van de host
    String url2 = "/smarttech/index.php";
    Serial.print("Requesting URL: ");
    Serial.println(url2);

    //
    //
    client.print(String("GET ") + url2 + " HTTP/1.1\r\n" +
                 "Host: " + host + "\r\n" +
                 "Connection: close\r\n\r\n");
    delay(500);

    // Alles lezen en afprinten naar de seriele poort.
    // Merk op dat ook de antwoord headers worden afgedrukt!
    while (client.available()) {
      String line = client.readStringUntil('\n');
      Serial.println(line);

      // detect status, create response
      if (line.indexOf("code-success") != -1) {
        int check = line.indexOf("code-success");
        String codeStatus = "Succes";
        Serial.print(codeStatus);
        digitalWrite(ledPins[2], HIGH);

        //buzzer
        tone(buzzer, 1000); // Send 1KHz sound signal...
        delay(500);        // ...for 0.5 sec
        noTone(buzzer);     // Stop sound...
        delay(500);

      }
      if (line.indexOf("code-error") != -1) {
        int check = line.indexOf("code-error");
        String codeStatus = "Error";
        Serial.print(codeStatus);
        digitalWrite(ledPins[3], HIGH);

        //buzzer
        tone(buzzer, 1000); // Send 1KHz sound signal...
        delay(3000);        // ...for 0.5 sec
      }
    }
    /**/

    /**/
    // De verbinding met de server sluiten
    Serial.println();
    Serial.println("closing connection");
    client.stop();
    delay(30000);
  }
  //RESET
  digitalWrite(ledPins[2], LOW);
  digitalWrite(ledPins[3], LOW);
  noTone(buzzer);     // Stop sound...
  delay(500);
  data="";

}
