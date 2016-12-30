/*  
 *  GPRS+GPS Quadband Module (SIM908)
 *  
 *  Copyright (C) Libelium Comunicaciones Distribuidas S.L. 
 *  http://www.libelium.com 
 *  
 *  This program is free software: you can redistribute it and/or modify 
 *  it under the terms of the GNU General Public License as published by 
 *  the Free Software Foundation, either version 3 of the License, or 
 *  (at your option) any later version. 
 *  a
 *  This program is distributed in the hope that it will be useful, 
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of 
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
 *  GNU General Public License for more details.
 *  
 *  You should have received a copy of the GNU General Public License 
 *  along with this program.  If not, see http://www.gnu.org/licenses/. 
 *  
 *  Version:           2.0
 *  Design:            David Gascón 
 *  Implementation:    Alejandro Gallego & Marcos Martinez
 */

 //Include arduPi library
#include "arduPi.h"

 int8_t sendATcommand2(const char* ATcommand, const char* expected_answer1, const char* expected_answer2, unsigned int timeout);
 int8_t sendATcommand(const char* ATcommand, const char* expected_answer, unsigned int timeout);
 void power_on();


int8_t answer;
int onModulePin= 2;
char aux_str[100];

char pin[]="0000";
char apn[]="fetnet01";//電信公司
char user_name[]="";
char password[]="";
char IP_address[]="210.241.199.199";
char port[]="8080";

char ip_data[40]="Test string from GPRS shield\r\n";



void setup(){

    pinMode(onModulePin, OUTPUT);
    Serial.begin(115200);    
    
    printf("Starting...\n");
    power_on();
    
    delay(3000);
    
	snprintf(aux_str, sizeof(aux_str), "AT+CPIN=%s", pin);
    sendATcommand2(aux_str, "OK", "ERROR", 2000);
    
    delay(3000);
    
    printf("Connecting to the network...\n");

    while( sendATcommand2("AT+CREG?", "+CREG: 0,1", "+CREG: 0,5", 1000)== 0 );

}


void loop(){
    
 
    // Selects Single-connection mode
    if (sendATcommand2("AT+CIPMUX=0", "OK", "ERROR", 1000) == 1)
    {
        // Waits for status IP INITIAL
        while(sendATcommand("AT+CIPSTATUS", "INITIAL", 500)  == 0 );
        delay(5000);
        
        snprintf(aux_str, sizeof(aux_str), "AT+CSTT=\"%s\",\"%s\",\"%s\"", apn, user_name, password);
        // Sets the APN, user name and password
        if (sendATcommand2(aux_str, "OK",  "ERROR", 30000) == 1)
        {            
            // Waits for status IP START
            while(sendATcommand("AT+CIPSTATUS", "START", 500)  == 0 );
            delay(5000);
            
            // Brings Up Wireless Connection
            if (sendATcommand2("AT+CIICR", "OK", "ERROR", 30000) == 1)
            {
                // Waits for status IP GPRSACT
                while(sendATcommand("AT+CIPSTATUS", "GPRSACT", 500)  == 0 );  //************GPRS天線
                delay(5000);
                
                // Gets Local IP Address
                if (sendATcommand2("AT+CIFSR", ".", "ERROR", 10000) == 1)
                {
                    // Waits for status IP STATUS
                    while(sendATcommand2("AT+CIPSTATUS", "IP STATUS", "", 500)  == 0 ); 
                    delay(5000);
                    printf("Opening TCP\n");
                    
                    snprintf(aux_str, sizeof(aux_str), "AT+CIPSTART=\"TCP\",\"%s\",\"%s\"", IP_address, port);
                    //**********HTTP是否有
                    // Opens a TCP socket
                    if (sendATcommand2(aux_str, "CONNECT OK", "CONNECT FAIL", 30000) == 1)
                    {
                        printf("Connected\n");
                        
                        // Sends some data to the TCP socket
                        sprintf(aux_str,"AT+CIPSEND=%d", strlen(ip_data));
                        if (sendATcommand2(aux_str, ">", "ERROR", 10000) == 1)    
                        {
                            sendATcommand2(ip_data, "SEND OK", "ERROR", 10000);
                        }
                        
                        // Closes the socket
                        sendATcommand2("AT+CIPCLOSE", "CLOSE OK", "ERROR", 10000);
                    }
                    else
                    {
                        printf("Error opening the connection\n");
                    }  
                }
                else
                {
                    printf("Error getting the IP address\n");
                }  
            }
            else
            {
                printf("Error bring up wireless connection\n");
            }
        }
        else
        {
            printf("Error setting the APN\n");
        } 
    }
    else
    {
        printf("Error setting the single connection\n");
    }
    
    sendATcommand2("AT+CIPSHUT", "OK", "ERROR", 10000);
    delay(10000);
}

void power_on(){

    uint8_t answer=0;
    
    // checks if the module is started
    answer = sendATcommand("AT", "OK", 2000);
    if (answer == 0)
    {
        // power on pulse
        digitalWrite(onModulePin,HIGH);
        delay(3000);
        digitalWrite(onModulePin,LOW);
    
        // waits for an answer from the module
        while(answer == 0){     // Send AT every two seconds and wait for the answer
            answer = sendATcommand("AT", "OK", 2000);    
        }
    }
    
}

int8_t sendATcommand(const char* ATcommand, const char* expected_answer, unsigned int timeout){

    uint8_t x=0,  answer=0;
    char response[100];
    unsigned long previous;

    memset(response, '\0', 100);    // Initialize the string

    delay(100);

    while( Serial.available() > 0) Serial.read();    // Clean the input buffer

    Serial.println(ATcommand);    // Send the AT command 


        x = 0;
    previous = millis();

    // this loop waits for the answer
    do{
        if(Serial.available() != 0){    
            // if there are data in the UART input buffer, reads it and checks for the asnwer
            response[x] = Serial.read();
            printf("%c",response[x]);
            x++;
            // check if the desired answer  is in the response of the module
            if (strstr(response, expected_answer) != NULL)    
            {
				printf("\n");
                answer = 1;
            }
        }
    }
    // Waits for the asnwer with time out
    while((answer == 0) && ((millis() - previous) < timeout));    

        return answer;
}

int8_t sendATcommand2(const char* ATcommand, const char* expected_answer1, 
        const char* expected_answer2, unsigned int timeout){

    uint8_t x=0,  answer=0;
    char response[100];
    unsigned long previous;

    memset(response, '\0', 100);    // Initialize the string

    delay(100);

    while( Serial.available() > 0) Serial.read();    // Clean the input buffer

    Serial.println(ATcommand);    // Send the AT command 

    x = 0;
    previous = millis();

    // this loop waits for the answer
    do{
        // if there are data in the UART input buffer, reads it and checks for the asnwer
        if(Serial.available() != 0){    
            response[x] = Serial.read();
            printf("%c",response[x]);
            x++;
            // check if the desired answer 1  is in the response of the module
            if (strstr(response, expected_answer1) != NULL)    
            {
				printf("\n");
                answer = 1;
            }
            // check if the desired answer 2 is in the response of the module
            else if (strstr(response, expected_answer2) != NULL)    
            {
				printf("\n");
                answer = 2;
            }
        }
    }
    // Waits for the asnwer with time out
    while((answer == 0) && ((millis() - previous) < timeout));    

    return answer;
}

int main (){
    setup();
    while(1){
        loop();
    }
    return (0);
}

    