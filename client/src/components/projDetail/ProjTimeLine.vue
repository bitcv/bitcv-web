<template>
  <div class="timeline">
    <div class="line-wrapper">
      <div class="event-item" v-for="(eventItem, index) in projEvent" :key="index">
        <div class="event-time-container">
          <div class="event-time">{{eventItem.eventKey}}</div>
        </div>
        <div v-for="(eventNode, subIndex) in eventItem.eventNode" :key="subIndex" class="event-node">
          <div class="event-title">{{eventNode.time}}</div>
          <div class="event-info"><span>{{ eventNode.title }}</span></div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    projEvent: Array
  },
  data () {
    return {
      eventList: [
        {
          eventTime: 2015,
          eventNode: [
            {
              title: '第2-3季度',
              info: '12345<br/>23456<br/>34567<br/>45678<br/>23456<br/>23456<br/>23456<br/>23456<br/>23456'
            }, {
              title: '4季度',
              info: '12345<br/>23456<br/>34567<br/>45678<br/>23456'
            }
          ]
        }, {
          eventTime: 2016,
          eventNode: [
            {
              title: '第2-3季度',
              info: '12345<br/>23456<br/>34567<br/>45678<br/>23456<br/>23456<br/>23456'
            }
          ]
        }, {
          eventTime: 2017,
          eventNode: [
            {
              title: '第2-3季度',
              info: '12345<br/>23456<br/>34567<br/>45678<br/>23456<br/>23456<br/>23456'
            }, {
              title: '4季度',
              info: '12345<br/>23456<br/>34567<br/>45678<br/>23456'
            }
          ]
        }

      ]
    }
  }
}
</script>
<style lang="scss" scoped>
  .timeline {
    width: 100%;
    .line-wrapper {
      position: relative;
      padding: 65px 0;
      width: 50%;
      height: 100%;
      border-right: 1px solid black;
      box-sizing: border-box;
      .event-item {
        width: 100%;
        font-size: 14px;
        margin-bottom: 20px;
        .event-time-container {
          display: flex;
        }
        .event-time {
          position: relative;
          margin: 0 24px;
          border-radius: 2px;
          width: 120px;
          height: 24px;
          text-align: center;
          line-height: 24px;
          color: #FFCF81;
          background-color: #4A4A4A;
          &:before {
            top: 7px;
            position: absolute;
            z-index: 2;
            content: '';
            border: 3px solid #4A4A4A;
            border-radius: 50%;
            width: 6px;
            height: 6px;
            background-color: #FFCF81;
          }
          &:after {
            content: '';
            position: absolute;
            z-index: 1;
            top: 0;
            width: 24px;
            height: 50%;
            border-bottom: 1px solid #4A4A4A;
          }
        }
        .event-node {
          position: relative;
          margin-top: 20px;
          padding: 0 16px;
          &:before {
            content: '';
            position: absolute;
            top: 3px;
            width: 7px;
            height: 7px;
            background-color: #4A4A4A;
            border-radius: 50%;
          }
          .event-title {
            position: absolute;
            padding: 0 16px;
            width: 100%;
            box-sizing: border-box;
          }
        }
        &:nth-child(2n+1) {
          margin-left: 100%;
          .event-time-container {
            justify-content: flex-start;
          }
          .event-time {
            &:before {
              left: -30px;
            }
            &:after {
              left: -24px;
            }
          }
          .event-node {
            text-align: left;
            &:before {
              left: -3px;
            }
            .event-title {
              left: -100%;
              text-align: right;
            }
          }
        }
        &:nth-child(2n) {
          .event-time-container {
            justify-content: flex-end;
          }
          .event-time {
            &:before {
              right: -30px;
            }
            &:after {
              right: -24px;
            }
          }
          .event-node {
            text-align: right;
            &:before {
              right: -4px;
            }
            .event-title {
              right: -100%;
              text-align: left;
            }
          }
        }
      }
    }
  }
</style>
