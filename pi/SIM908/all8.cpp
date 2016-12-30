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
#include "Python.h"
int8_t sendATcommand(const char* ATcommand, const char* expected_answer, unsigned int timeout);
int8_t sendATcommand2(const char* ATcommand, const char* expected_answer1, const char* expected_answer2, unsigned int timeout);
void power_on();
void GPScatch();
void downloadFTP();
void upGPSFTP();
void configure_FTP();
void upnfcFTP();
void nfcftp();
void FTPALL();
void SMS();
void smssend();
int8_t answer;
int onModulePin= 2;
char aux_string[30];

/* *******GPS宣告*****/
int counter;
long previous;
char RMC_str[100];

/* *******sms宣告*****/
char phone_number[]="0972167116";   // ********* is the number to call
char sms_text[500]; 
/* *******FTP宣告*****/
char aux_str[50];
char aux_str1[50];//存指令
char change[200];//GPS檔名時間

//char pin[]="0000";
char apn[]="internet";
char user_name[]="";
char password[]="";
char ftp_server[]="ftp.byethost10.com";
char ftp_user_name[]="b10_19011039";
char ftp_password[]="ldes97161490";
char path[]="/htdocs/raspberry/GPS/";
char path1[]="/htdocs/raspberry/NFC/";
char file_name[200];
char nfcname[50];
char username[50];
char test_str[223];
char user[200];
char nfc_text[130];
char GPS_text[41];
char NE_text[500];
int data_size, aux;
void exec_python_file(){//呼叫python檔
    //初始化
    Py_Initialize();
    //choose1，执行单纯的内嵌字符串python代码，建议使用
    if(!PyRun_SimpleString("execfile('/home/pi/mpufinal02.py')"))
        printf("execute python file program failed");
		printf("\n");
    //释放资源
    Py_Finalize();
}    

void setup(){
    pinMode(onModulePin, OUTPUT);
    Serial.begin(115200);       
    printf("Starting...\n");
    power_on();    
    delay(3000);       
    while( (sendATcommand("AT+CREG?", "+CREG: 0,1", 500) || 
            sendATcommand("AT+CREG?", "+CREG: 0,5", 500)) == 0 ); 
}


void loop(){//一直跑陀螺儀的值,超過值就傳簡訊	
	delay(500);	
	//*******************讀USER
	FILE *fPtr1; //read	
	char data3[1024];	
	char name[1024];	
	fPtr1=fopen("nfcdata.txt","r");	
	if ( fPtr1 ==NULL ) { printf("開讀檔失敗!"); } /* 處裡開讀檔失敗的情形 */
	for(int a=0;a<20;a++){
		fscanf(fPtr1 , " %s " , &data3[a]); /* 讀入100個字元到 data[100] */		
		if(a==1){			
			sprintf(name,"%s.txt", &data3[a]);			
			strcat(username, name);//user變檔名
		}	
	}
	fclose(fPtr1);	
	delay(500);	
	GPScatch();//GPS 抓值	
	SMS();//SMS	
	exec_python_file();//呼叫python檔	
	delay(500);//讓陀螺儀有時間寫入mpureturn.txt
	FTPALL();//FTP傳GPS資料		
	//***********讀檔是否傳簡訊	
	/*
	FILE *fp1;
    fp1 = fopen("mpureturn.txt", "r");	
	char data[200];
	char data1[200];
	for(int a=0;a<2;a++){
		fscanf(fp1 , " %s " , &data[a]); /* 讀入100個字元到 data[100] 		
		if(a==0){
			sprintf(data1,"%s", &data[a]);//值存到data1			
			if(strcmp(data1,"sms")== 0)
			{
				smssend();//傳簡訊				
			}
			if(strcmp(data1,"bluetooth")== 0)
			{
							
			}
			
			
		}			
	}	
	fclose(fp1);	
	delay(3000);	
	FTPALL();//FTP傳GPS資料	
	delay(5000);	
	*/
	
	
	
	/*
	//刪檔案
	char GPSpath1[200]="/home/pi/cooking/arduPi/GPSNOW/";	
	strcat(GPSpath1, change);
	
	if(remove(GPSpath1)) 
    printf("不能刪除 %s 此檔案\n",GPSpath1); 
    else printf("刪除 OK \n");
	*/	
	//清空陣列
	for(int i=0;i<500;i++){			
			change[i]='\0';//清空change陣列		
			sms_text[i]='\0';//清空sms_text陣列	
	}	
}
void GPScatch(){//GPS抓值    
    // waits for fix GPS
    while( (sendATcommand("AT+CGPSSTATUS?", "2D Fix", 5000) || 
            sendATcommand("AT+CGPSSTATUS?", "3D Fix", 5000)) == 0 );//???如果兩個都等於零就DO NOTHING
	//**********************************************************
    // RMC
    // Clean the input buffer
    while( Serial.available() > 0) Serial.read();           
    delay(100);
    // request RMC string 
    sendATcommand("AT+CGPSINF=32", "AT+CGPSINF=32\r\n\r\n", 2000);    
    counter = 0;
    answer = 0;
    memset(RMC_str, '\0', 100);    // Initialize the string
    previous = millis();
    // this loop waits for the NMEA string
    do{

        if(Serial.available() != 0){    
            RMC_str[counter] = Serial.read();
            counter++;
            // check if the desired answer is in the response of the module
            if (strstr(RMC_str, "OK") != NULL)    
            {
                answer = 1;
            }
        }
        // Waits for the asnwer with time out
    }while((answer == 0) && ((millis() - previous) < 2000));     
    RMC_str[counter-3] = '\0';    
    /*所有的GPSDATA**************************************************/ 	
    printf("RMC string: ");
    printf("%s\n",RMC_str);	  
	/*只讀經緯度***************************************************/
	FILE *fp;	
    fp = fopen("GPSdata.txt", "a+");		
		char old[200];//小時加8後時間
		char *s = strtok(RMC_str, ","); //分割的判斷字元
		char *put[100]; //分割後放入新的字串陣列
		int s_count=0; //分幾個了		
		char time[200];
		char GPSpath[200]="/home/pi/cooking/arduPi/GPSNOW/";		
		while(s != NULL) {
		put[s_count++]=s;  //把分出來的丟進去 結果陣列
		s = strtok(NULL, ","); //直到切完所有TOKEN 結束
		}
		for(int x=0;x<s_count;x++)//印出結果
		{//只存經緯度
			if(x==1)
			{
				sprintf(time,"%s", put[x]);				
				char top[2];
				top[0]=time[0];
				top[1]=time[1];				
				int ctop=atoi(top);
				ctop=ctop+8;
				sprintf(old,"%d", ctop);				
				char tail[4];
				tail[0]=time[2];
				tail[1]=time[3];
				tail[2]=time[4];
				tail[3]=time[5];				
				strcat(old, tail);				
			}
			//把日期變成年月日排
			if(x==9)
			{
				char date[6];
				char tmp[2];				
				sprintf(date,"%s", put[x]);//日期
				//把日期變成年月日排
				tmp[0]=date[0];
				tmp[1]=date[1];
				date[0]=date[4];
				date[1]=date[5];
				date[4]=tmp[0];
				date[5]=tmp[1];
				strcat(date, old);				
				strcat(change, date);//日期+時間
				strcat(change,username);//日期+時間+USER			
				strcat(GPSpath, date);//路徑+日期+時間+USER
				strcat(GPSpath,username);			
				for(int i=0;i<6;i++){			
					date[i]='\0';//清空change陣列			
				}				
			}			
		}		
		FILE *fp1;		
		fp1 = fopen(GPSpath, "w");		
		for(int x=0;x<s_count;x++)//印出結果
		{//只存經緯度
			if(x==1)
			{
				fprintf(fp,"%s,", put[x]);
				fprintf(fp1,"%s,", put[x]);  
			}
			if(x==3)
			{
				fprintf(fp,"%s,", put[x]);
				fprintf(fp1,"%s,", put[x]);
			}
			if(x==4)
			{
				fprintf(fp,"%s,", put[x]);
				fprintf(fp1,"%s,", put[x]);
			}
			if(x==5)
			{
				fprintf(fp,"%s,", put[x]);
				fprintf(fp1,"%s,", put[x]);
			}
			if(x==6)
			{
				fprintf(fp,"%s\n", put[x]);
				fprintf(fp1,"%s\n", put[x]);
			}
		}
	fclose(fp1);	
    fclose(fp);    
    delay(5000);
}
void nfcftp(){//傳NFC檔	
	/*讀NFC內容******************************************************/
	FILE *fPtr1; //read	
	char data1[1024];
	char data2[1024];
	char name[1024];	
	fPtr1=fopen("nfcdata.txt","r");	
	if ( fPtr1 ==NULL ) { printf("開讀檔失敗!"); } /* 處裡開讀檔失敗的情形 */
	for(int a=0;a<20;a++){
		fscanf(fPtr1 , " %s " , &data1[a]); /* 讀入100個字元到 data[100] */		
		if(a==1){
			sprintf(data2,"caller:%s\n", &data1[a]);//值存到data1
			strcat(nfc_text, data2);
			sprintf(user,"%s", &data1[a]);
			sprintf(name,"%s.txt", &data1[a]);			
			strcat(nfcname, name);//user變檔名
		}
		if(a==2){			
			sprintf(data2,"bloodtype:%s\n", &data1[a]);
			strcat(nfc_text, data2);
		}
		if(a==3){			
			sprintf(data2,"ID:%s\n", &data1[a]);
			strcat(nfc_text, data2);
		}
		if(a==4){
			sprintf(data2,"Plate:%s\n", &data1[a]);
			strcat(nfc_text,data2);
		}
		if(a==5){			
			sprintf(data2,"Emergency Contact:%s\n", &data1[a]);
			strcat(nfc_text, data2);
		}
		if(a==6){			
			sprintf(data2,"Contact num:%s\n", &data1[a]);
			strcat(nfc_text,  data2);
		}
	}
	fclose(fPtr1);		
	printf("\n");
	/*讀NFC內容******************************************************/	
	upnfcFTP();	
}
void configure_FTP(){
    // sets APN, user name and password
    sendATcommand("AT+SAPBR=3,1,\"Contype\",\"GPRS\"", "OK", 2000);
    snprintf(aux_str, sizeof(aux_str), "AT+SAPBR=3,1,\"APN\",\"%s\"", apn);
    sendATcommand(aux_str, "OK", 2000);    
    snprintf(aux_str, sizeof(aux_str), "AT+SAPBR=3,1,\"USER\",\"%s\"", user_name);
    sendATcommand(aux_str, "OK", 2000);    
    snprintf(aux_str, sizeof(aux_str), "AT+SAPBR=3,1,\"PWD\",\"%s\"", password);
    sendATcommand(aux_str, "OK", 2000);  
    // sets the paremeters for the FTP server
	sendATcommand("AT+SAPBR=0,1", "OK", 5000);//關網路
    while (sendATcommand("AT+SAPBR=1,1", "OK", 20000) != 1);   
    sendATcommand("AT+FTPCID=1", "OK", 2000);
    snprintf(aux_str, sizeof(aux_str), "AT+FTPSERV=\"%s\"", ftp_server);
    sendATcommand(aux_str, "OK", 2000);
    sendATcommand("AT+FTPPORT=21", "OK", 2000);
    snprintf(aux_str, sizeof(aux_str), "AT+FTPUN=\"%s\"", ftp_user_name);
    sendATcommand(aux_str, "OK", 2000);
    snprintf(aux_str, sizeof(aux_str), "AT+FTPPW=\"%s\"", ftp_password);
    sendATcommand(aux_str, "OK", 2000);	
}
void upGPSFTP(){
    snprintf(aux_str, sizeof(aux_str), "AT+FTPPUTNAME=\"%s\"", file_name);
    sendATcommand(aux_str, "OK", 2000);
    snprintf(aux_str, sizeof(aux_str), "AT+FTPPUTPATH=\"%s\"", path);
    sendATcommand(aux_str, "OK", 2000);  
    if (sendATcommand("AT+FTPPUT=1", "+FTPPUT:1,1,", 30000) == 1)
    {
        data_size = 0;
        while(Serial.available()==0);
        aux = Serial.read();
        do{
            data_size *= 10;
            data_size += (aux-0x30);
            while(Serial.available()==0);
            aux = Serial.read();        
        }
        while(aux != 0x0D);
		int a=sizeof(GPS_text);
        if (data_size >=a)
        { 
			snprintf(aux_str, sizeof(aux_str), "AT+FTPPUT=2,%d", a);//存資料個數
			snprintf(aux_str1, sizeof(aux_str), "+FTPPUT:2,%d", a);//存資料個數
            if (sendATcommand(aux_str,aux_str1, 30000) == 1)
            {
                sendATcommand(GPS_text, "+FTPPUT:1,1", 30000);          
                sendATcommand("AT+FTPPUT=2,0", "+FTPPUT:1,0", 30000);
                printf("Upload done!!\n");
            }			
            else 
            {
                sendATcommand("AT+FTPPUT=2,0", "OK", 30000);                    
            }
        }
        else
        {
            sendATcommand("AT+FTPPUT=2,0", "OK", 30000); 
        }
    }
    else
    {
        printf("Error opening the FTP session\n");
    }	
	delay(3000);
	/*//刪檔案
	char GPSpath[200]="/home/pi/cooking/arduPi/GPSNOW/";	
	strcat(GPSpath, change);
	
	if(remove(GPSpath)) 
    printf("不能刪除 %s 此檔案\n",GPSpath); 
    else printf("刪除 OK \n");
	*/
}
void upnfcFTP(){
    snprintf(aux_str, sizeof(aux_str), "AT+FTPPUTNAME=\"%s\"", nfcname);
    sendATcommand(aux_str, "OK", 2000);
    snprintf(aux_str, sizeof(aux_str), "AT+FTPPUTPATH=\"%s\"", path1);
    sendATcommand(aux_str, "OK", 2000);  
    if (sendATcommand("AT+FTPPUT=1", "+FTPPUT:1,1,", 30000) == 1)
    {
        data_size = 0;
        while(Serial.available()==0);
        aux = Serial.read();
        do{
            data_size *= 10;
            data_size += (aux-0x30);
            while(Serial.available()==0);
            aux = Serial.read();        
        }
        while(aux != 0x0D);
		int a=sizeof(nfc_text);
        if (data_size >=a)
        { 
			snprintf(aux_str, sizeof(aux_str), "AT+FTPPUT=2,%d", a);//存資料個數
			snprintf(aux_str1, sizeof(aux_str), "+FTPPUT:2,%d", a);//存資料個數
            if (sendATcommand(aux_str,aux_str1, 30000) == 1)
            {
                sendATcommand(nfc_text, "+FTPPUT:1,1", 30000);          
                sendATcommand("AT+FTPPUT=2,0", "+FTPPUT:1,0", 30000);
                printf("Upload done!!\n");
            }			
            else 
            {
                sendATcommand("AT+FTPPUT=2,0", "OK", 30000);                    
            }
        }
        else
        {
            sendATcommand("AT+FTPPUT=2,0", "OK", 30000); 
        }
    }
    else
    {
        printf("Error opening the FTP session\n");
    }
}
void FTPALL(){
	/////////////////////////////////////////////////////////////////////////////
	
	
	char GPSpath[200]="/home/pi/cooking/arduPi/GPSNOW/";	
	strcat(GPSpath, change);	
	strcat(file_name, change);	
	FILE *fp1;
    fp1 = fopen(GPSpath, "r");	
	char data[200];
	char data1[200];
	for(int a=0;a<2;a++){
		fscanf(fp1 , " %s " , &data[a]); /* 讀入100個字元到 data[100] */		
		if(a==0){
			sprintf(data1,"%s", &data[a]);//值存到data1
			strcat(GPS_text, data1);						
		}			
	}
	fclose(fp1);    
	printf("\n");
	
	
	//讀檔案
	FILE *fp5;
    fp5 = fopen("mpureturn.txt", "r");	
	char data4[200];
	char data5[200];
	char ret[2];
	for(int a=0;a<2;a++){
		fscanf(fp5 , " %s " , &data4[a]); //讀入100個字元到 data[100] 		
		if(a==0){
			sprintf(data5,"%s", &data4[a]);//值存到data1			
			
			if(strcmp(data5,"bluetooth")== 0)
			{
				for(int i=0;i<2;i++){
					ret[i]='\0';			
				}				
				strcat(ret,",1");						
			}
			if(strcmp(data5,"sms")== 0)
			{
				for(int i=0;i<2;i++){
					ret[i]='\0';			
				}	
				strcat(ret,",2");
				smssend();//傳簡訊						
			}	
			else
			{
				for(int i=0;i<2;i++){
					ret[i]='\0';			
				}	
				strcat(ret,",0");
			}
				strcat(GPS_text, ret);//bluetooth-1 sms-2			
		}			
	}	
	fclose(fp5);	
	printf(GPS_text);
	printf("\n");	
	delay(5000);
	///////////////////////////////////////////////////////////////////////////////	
	
	upGPSFTP();//傳FTP	
	
	
	for(int i=0;i<41;i++){
			GPS_text[i]='\0';//清空陣列			
		} 	
	for(int i=0;i<200;i++){
			GPSpath[i]='\0';//清空GPSpath陣列			
			file_name[i]='\0';//清空file_name陣列
		}		
}
void SMS(){
    //*****************SMS*****************************************
	/*讀SMS GPS檔***************************************************/
	for(int i=0;i<500;i++){			
			sms_text[i]='\0';//清空sms_text陣列			
	}
	char temp[500]="Help!I have an accident.\nloaction:"; 
	strcat(sms_text,temp);
	char data1[500];
	char data2[500];
	char da1[500];	
	char GPSpath[200]="/home/pi/cooking/arduPi/GPSNOW/";	
	strcat(GPSpath, change);	
	/*讀GPS檔***************************************************/
	FILE *fPtr2; 	
	fPtr2=fopen(GPSpath,"r");	//read	
	if ( fPtr2 ==NULL ) { printf("開讀檔失敗!"); } /* 處裡開讀檔失敗的情形 */			
		/*只讀緯度***************************************************/				
				char ntop[2];
				char ntail[8];
				fseek(fPtr2,11,SEEK_SET);				
				fread(ntop,sizeof(char),2,fPtr2); 
				//printf("%s\n",ntop);
				sprintf(data2,"%s ", ntop);
				strcat(sms_text, data2);				
				fseek(fPtr2,13,SEEK_SET);				
				fread( ntail,sizeof(char),9,fPtr2); 
				//printf("%s\n", ntail);
				sprintf(data2,"%s,", ntail);
				strcat(sms_text, data2);
		/*只讀經度***************************************************/	
				char etop[3];
				char etail[8];
				fseek(fPtr2,25,SEEK_SET);				
				fread(etop,sizeof(char),3,fPtr2); 
				//printf("%s\n",etop);
				sprintf(data2,"%s ", etop);
				strcat(sms_text, data2);				
				fseek(fPtr2,28,SEEK_SET);				
				fread( etail,sizeof(char),9,fPtr2); 
				//printf("%s", etail);		
				sprintf(data2,"%s\n", etail);
				strcat(sms_text, data2);
	for(int i=0;i<200;i++){			
		GPSpath[i]='\0';//清空change陣列			
	}	
	fclose(fPtr2);
	/*讀NFC內容******************************************************/
	FILE *fPtr1; //read
	fPtr1=fopen("nfcdata.txt","r");//READ	
	if ( fPtr1 ==NULL ) { printf("開讀檔失敗!"); } /* 處裡開讀檔失敗的情形 */
	for(int a=0;a<10;a++){
		fscanf(fPtr1 , " %s " , &data1[a]); 	
		if(a==1){
			sprintf(data2,"caller:%s\n", &data1[a]);
			strcat(sms_text, data2);			
		}
		if(a==2){			
			sprintf(data2,"blood:%s\n", &data1[a]);
			strcat(sms_text, data2);
		}
		if(a==3){			
			sprintf(data2,"ID:%s\n", &data1[a]);
			strcat(sms_text, data2);
		}
		if(a==4){
			sprintf(data2,"Car:%s\n", &data1[a]);
			strcat(sms_text,data2);
		}
		if(a==5){			
			sprintf(data2,"Family:%s\n", &data1[a]);
			strcat(sms_text, data2);
		}
		if(a==6){			
			sprintf(data2,"Num:%s", &data1[a]);
			strcat(sms_text,  data2);
		}
	}	
	fclose(fPtr1);	
	//printf(sms_text);//印出簡訊內容
	printf("\n");   	
}
void smssend(){	
	printf("\n");
	sendATcommand("AT+CMGF=1", "OK", 1000);    // sets the SMS mode to text    
    sprintf(aux_string,"AT+CMGS=\"%s\"", phone_number);
    answer = sendATcommand(aux_string, ">", 2000);    // send the SMS number
    if (answer == 1)
    {
        Serial.println(sms_text);
        Serial.write(0x1A);
        answer = sendATcommand("", "OK", 20000);
        if (answer == 1)
        {
            printf("Sent \n");    
        }
        else
        {
            printf("error \n");
        }
    }
    else
    {
        printf("error %o\n",answer);
    }	
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
	setup();//開機
	configure_FTP();//設定	
	nfcftp();//NFC資料傳一次就好	
    while(1){
        loop();
    }
    return (0);
}   