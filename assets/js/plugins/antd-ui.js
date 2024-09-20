import {
  Alert,
  AutoComplete,
  Avatar,
  // BackTop,
  Badge,
  Breadcrumb,
  Button,
  Calendar,
  Card,
  Carousel,
  Cascader,
  Checkbox,
  Col,
  Collapse,
  // Comment,
  // ConfigProvider,
  DatePicker,
  Divider,
  Drawer,
  Dropdown,
  Empty,
  Form,
  Icon,
  Input,
  InputNumber,
  Layout,
  List,
  LocaleProvider,
  Menu,
  message,
  Modal,
  notification,
  Pagination,
  Popconfirm,
  Popover,
  // Progress,
  Radio,
  // Rate,
  Row,
  Select,
  Skeleton,
  Slider,
  Spin,
  Steps,
  Switch,
  Table,
  Tabs,
  Tag,
  Timeline,
  TimePicker,
  Tooltip,
  Tree,
  TreeSelect,
  // TreeNode,
  Upload
} from "ant-design-vue";
import Vue from "vue";

Vue.prototype.$message = message;
Vue.prototype.$notification = notification;
Vue.prototype.$info = Modal.info;
Vue.prototype.$success = Modal.success;
Vue.prototype.$error = Modal.error;
Vue.prototype.$warning = Modal.warning;
Vue.prototype.$confirm = Modal.confirm;

// Vue.use(Affix);
Vue.use(Alert);
// Vue.use(Anchor);
Vue.use(AutoComplete);
Vue.use(Avatar);
// Vue.use(BackTop);
Vue.use(Badge);
Vue.use(Breadcrumb);
Vue.use(Button);
Vue.use(Button.Group);
Vue.use(Calendar);
Vue.use(Card);
Vue.use(Carousel);
Vue.use(Cascader);
Vue.use(Checkbox);
Vue.use(Col);
Vue.use(Collapse);
// Vue.use(Comment);
// Vue.use(ConfigProvider);
Vue.use(DatePicker);
Vue.use(Divider);
Vue.use(Drawer);
Vue.use(Dropdown);
Vue.use(Empty);
Vue.use(Form);
Vue.use(Icon);
Vue.use(Input);
Vue.use(InputNumber);
Vue.use(Layout);
Vue.use(List);
Vue.use(LocaleProvider);
Vue.use(Menu);
Vue.use(Modal);
Vue.use(Pagination);
Vue.use(Popconfirm);
Vue.use(Popover);
// Vue.use(Progress);
Vue.use(Radio);
// Vue.use(Rate);
Vue.use(Row);
// Vue.use(TreeNode);
Vue.use(Select);
Vue.use(Skeleton);
Vue.use(Slider);
Vue.use(Spin);
Vue.use(Steps);
Vue.use(Switch);
Vue.use(Table);
Vue.use(Tabs);
Vue.use(Tag);
Vue.use(Timeline);
Vue.use(TimePicker);
Vue.use(Tooltip);
// Vue.use(Transfer);
Vue.use(Tree);
Vue.use(TreeSelect);
Vue.use(Upload);
